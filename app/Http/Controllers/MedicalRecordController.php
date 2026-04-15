<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicalRecordController extends Controller
{
    private function normalizePhone(?string $phone): ?string
    {
        if ($phone === null) {
            return null;
        }

        $digits = preg_replace("/\D+/", "", $phone);
        $digits = $digits !== null ? trim($digits) : "";

        return $digits !== "" ? $digits : null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $events = $user->hasRole("Super Admin")
            ? Event::latest()->get()
            : $user->events()->latest()->get();

        return view("pages.medical-records.index", compact("events"));
    }

    /**
     * Import a collection of medical records from CSV and store them
     */
    public function import(Request $request)
    {
        $validated = $request->validate([
            "event_id" => "required|exists:events,id",
            "csv_file" => "required|file|mimes:csv,txt",
            "destroy_date" => "required|date",
            "acknowledge" => "required",
        ]);

        try {
            $file = $request->file("csv_file");
            $eventId = $validated["event_id"];
            $event = Event::find($eventId);
            $destroyDate = $validated["destroy_date"];
            $path = $file->getRealPath();

            // Start a transaction
            DB::beginTransaction();

            // Replace import: remove existing records for this event first so
            // participant_id unique constraint is not violated on re-import.
            $event->medicalRecords()->delete();

            $participants = EventParticipant::where("event_id", $eventId)
                ->select(["id", "phone"])
                ->get();

            $participantPhoneToId = [];
            foreach ($participants as $p) {
                $normalized = $this->normalizePhone($p->phone);
                if ($normalized) {
                    $participantPhoneToId[$normalized] = $p->id;
                }
            }

            // Parse CSV content
            $fileContent = file_get_contents($path);

            $bom = pack("H*", "EFBBBF");
            $fileContent = preg_replace("/^$bom/", "", $fileContent);

            $handle = fopen("php://memory", "rw");
            fwrite($handle, $fileContent);
            rewind($handle);

            $firstLine = fgets($handle);
            $delimiter = ",";
            if (strpos($firstLine, ";") !== false) {
                $delimiter = ";";
            } elseif (strpos($firstLine, "\t") !== false) {
                $delimiter = "\t";
            }
            rewind($handle);

            // Skip header
            fgetcsv($handle, 10000, $delimiter);

            $created = 0;
            $updated = 0;
            $skippedEmpty = 0;
            $skippedNoMobile = 0;
            $unlinked = 0;

            while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) {
                if (empty(array_filter($row))) {
                    $skippedEmpty++;
                    continue;
                }

                if (
                    empty(trim($row[0])) &&
                    \count($row) > 22 &&
                    !empty(trim($row[1]))
                ) {
                    array_shift($row);
                }

                $row = array_map(
                    fn($value) => \is_string($value) ? trim($value) : $value,
                    $row,
                );

                $dob = null;

                if (isset($row[14]) && !empty($row[14])) {
                    try {
                        $dob = Carbon::parse(
                            str_replace("/", "-", $row[14]),
                        )->format("Y-m-d");
                    } catch (\Exception $e) {
                        $dob = null;
                    }
                }

                // Collect record content
                $content = [
                    "event_id" => $eventId,
                    "vehicle" => $row[0] ?? null,
                    "first_name" => $row[1] ?? null,
                    "last_name" => $row[2] ?? null,
                    "nickname" => $row[3] ?? null,
                    "address1" => $row[4] ?? null,
                    "address2" => $row[5] ?? null,
                    "address3" => $row[6] ?? null,
                    "address4" => $row[7] ?? null,
                    "address5" => $row[8] ?? null,
                    "address6" => $row[9] ?? null,
                    "mobile" => $row[10] ?? null,
                    "next_of_kin" => $row[11] ?? null,
                    "nok_phone" => $row[12] ?? null,
                    "nok_alt_phone" => $row[13] ?? null,
                    "dob" => $dob,
                    "allergies" => $row[15] ?? null,
                    "dietary_requirement" => $row[16] ?? null,
                    "past_medical_history" => $row[17] ?? null,
                    "current_medical_history" => $row[18] ?? null,
                    "current_medications" => $row[19] ?? null,
                ];

                $normalizedMobile = $this->normalizePhone(
                    is_string($content["mobile"]) ? $content["mobile"] : null,
                );

                if (!$normalizedMobile) {
                    $skippedNoMobile++;
                    $participantId = null;
                    $unlinked++;
                } else {
                    $participantId = $participantPhoneToId[$normalizedMobile] ?? null;
                    if (!$participantId) {
                        $unlinked++;
                    }
                }

                // Linked participants are unique by participant_id (DB unique index).
                // The same phone can appear on multiple CSV rows — upsert instead of insert.
                if ($participantId !== null) {
                    $record = MedicalRecord::updateOrCreate(
                        ["participant_id" => $participantId],
                        [
                            "event_id" => $eventId,
                            "content" => json_encode($content),
                            "imported_at" => now(),
                            "expires_at" => $destroyDate,
                        ],
                    );
                    if ($record->wasRecentlyCreated) {
                        $created++;
                    } else {
                        $updated++;
                    }
                } else {
                    MedicalRecord::create([
                        "event_id" => $eventId,
                        "participant_id" => null,
                        "content" => json_encode($content),
                        "imported_at" => now(),
                        "expires_at" => $destroyDate,
                    ]);
                    $created++;
                }
            }

            fclose($handle);
            DB::commit();

            $message =
                "Import completed: {$created} created" .
                ($updated ? ", {$updated} updated (duplicate rows for same participant)" : "") .
                ($skippedEmpty ? ", {$skippedEmpty} empty rows skipped" : "") .
                ($skippedNoMobile
                    ? ", {$skippedNoMobile} rows missing mobile (imported but unlinked)"
                    : "") .
                ($unlinked ? ", {$unlinked} records not linked to a participant" : "") .
                ".";

            return back()->with("success", $message);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("Medical record import failed", [
                "event_id" => $request->input("event_id"),
                "error" => $e->getMessage(),
                "line" => $e->getLine(),
                "file" => $e->getFile(),
            ]);

            return back()->withErrors([
                "import" =>
                    "Medical record import failed: " . $e->getMessage(),
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $event = Event::findOrFail($id);
        $records = $event->medicalRecords;
        return view("pages.medical-records.show", compact("records", "event"));
    }

    /**
     * Display individual record
     */
    public function showRecord(Event $event, MedicalRecord $record)
    {
        $record->loadMissing(["participant", "comments", "images"]);

        return view(
            "pages.medical-records.show-record",
            compact("event", "record"),
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $event = Event::findOrFail($id);
        $event->medicalRecords()->delete();

        return redirect()
            ->route("medical-records.index")
            ->with("success", "Medical records deleted");
    }
}
