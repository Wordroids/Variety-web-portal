<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordCollection;
use App\Models\MedicalRecordItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
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
            "acknowledge" => "required|string",
        ]);

        try {
            $file = $request->file("csv_file");
            $eventId = $validated["event_id"];
            $event = Event::find($eventId);
            $destroyDate = $validated["destroy_date"];
            $path = $file->getRealPath();

            // Start a transaction
            DB::beginTransaction();

            // Delete existing medical records (cascades to all related resources)
            if ($event->medicalRecordCollection) {
                $event->medicalRecords()->delete();
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

            while (($row = fgetcsv($handle, 10000, $delimiter)) !== false) {
                if (empty(array_filter($row))) {
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

                $participant = EventParticipant::where(
                    "phone",
                    $content["mobile"],
                )
                    ->where("event_id", $eventId)
                    ->first();

                if (!$participant) {
                    continue;
                }

                // Create record
                MedicalRecord::create([
                    "event_id" => $eventId,
                    "participant_id" => $participant->id,
                    "content" => json_encode($content),
                    "imported_at" => now(),
                    "expires_at" => $destroyDate,
                ]);
            }

            fclose($handle);
            DB::commit();

            return back()->with("success", "CSV imported successfully");
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(
                [
                    "success" => false,
                    "message" =>
                        "An error occurred during import: " .
                        $e->getMessage() .
                        " at line " .
                        $e->getLine(),
                ],
                500,
            );
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
