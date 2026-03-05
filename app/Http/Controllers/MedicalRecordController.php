<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MedicalRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::query();


        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('first_name', 'like', "%{$searchTerm}%")
                    ->orWhere('last_name', 'like', "%{$searchTerm}%")
                    ->orWhere('vehicle', 'like', "%{$searchTerm}%")
                    ->orWhere('mobile', 'like', "%{$searchTerm}%")
                    ->orWhere('address1', 'like', "%{$searchTerm}%");
            });
        }

        // Handle Filters
        if ($request->filled('filter')) {
            if ($request->filter === 'has_allergies') {
                $query->whereNotNull('allergies')->where('allergies', '!=', '');
            } elseif ($request->filter === 'has_medications') {
                $query->whereNotNull('current_medications')->where('current_medications', '!=', '');
            }
        }

        // Fetch records
        $records = $query->latest()->get();
        $events = \App\Models\Event::select('id', 'title')->latest()->get();
        return view('pages.medical-records.index', compact('records', 'events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.medical-records.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'address3' => 'nullable|string|max:255',
            'address4' => 'nullable|string|max:255',
            'address5' => 'nullable|string|max:255',
            'address6' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'next_of_kin' => 'nullable|string|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_alt_phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'allergies' => 'nullable|string',
            'dietary_requirement' => 'nullable|string',
            'past_medical_history' => 'nullable|string',
            'current_medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);



        return response()->json([
            'success' => true,
            'message' => 'Medical record saved successfully'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // return view('pages.medical-records.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'vehicle' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:255',
            'address1' => 'nullable|string|max:255',
            'address2' => 'nullable|string|max:255',
            'address3' => 'nullable|string|max:255',
            'address4' => 'nullable|string|max:255',
            'address5' => 'nullable|string|max:255',
            'address6' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'next_of_kin' => 'nullable|string|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_alt_phone' => 'nullable|string|max:20',
            'dob' => 'nullable|date',
            'allergies' => 'nullable|string',
            'dietary_requirement' => 'nullable|string',
            'past_medical_history' => 'nullable|string',
            'current_medical_history' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        try {
            $record = MedicalRecord::findOrFail($id);
            $record->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Medical record updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating record: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $record = MedicalRecord::findOrFail($id);
            $record->delete();

            return response()->json([
                'success' => true,
                'message' => 'Medical record deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting record: ' . $e->getMessage()
            ], 500);
        }
    }

    //to upload medical records via excel
    public function upload(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'csv_file' => 'required|file|mimes:csv,txt',
            'destroy_date' => 'required|date',
            'acknowledge' => 'required|string',
        ]);

        try {
            $file = $request->file('csv_file');
            $eventId = $validated['event_id'];
            $destroyDate = $validated['destroy_date'];
            $acknowledge = filter_var($validated['acknowledge'], FILTER_VALIDATE_BOOLEAN);

            $path = $file->getRealPath();

            DB::beginTransaction();

            if ($acknowledge) {
                MedicalRecord::where('event_id', $eventId)->delete();
            }


            $fileContent = file_get_contents($path);

            $bom = pack('H*', 'EFBBBF');
            $fileContent = preg_replace("/^$bom/", '', $fileContent);


            $handle = fopen('php://memory', 'rw');
            fwrite($handle, $fileContent);
            rewind($handle);


            $firstLine = fgets($handle);
            $delimiter = ',';
            if (strpos($firstLine, ';') !== false) {
                $delimiter = ';';
            } elseif (strpos($firstLine, "\t") !== false) {
                $delimiter = "\t";
            }
            rewind($handle);

            // Skip header
            fgetcsv($handle, 10000, $delimiter);

            while (($row = fgetcsv($handle, 10000, $delimiter)) !== FALSE) {

                if (empty(array_filter($row))) {
                    continue;
                }


                if (empty(trim($row[0])) && count($row) > 22 && !empty(trim($row[1]))) {
                    array_shift($row);
                }


                $row = array_map(function ($value) {
                    return is_string($value) ? trim($value) : $value;
                }, $row);

                $dob = null;

                if (isset($row[14]) && !empty($row[14])) {
                    try {
                        $dob = Carbon::parse(str_replace('/', '-', $row[14]))->format('Y-m-d');
                    } catch (\Exception $e) {
                        $dob = null;
                    }
                }

                MedicalRecord::create([
                    'event_id'                => $eventId,
                    'vehicle'                 => $row[0] ?? null,
                    'first_name'              => $row[1] ?? null,
                    'last_name'               => $row[2] ?? null,
                    'nickname'                => $row[3] ?? null,
                    'address1'                => $row[4] ?? null,
                    'address2'                => $row[5] ?? null,
                    'address3'                => $row[6] ?? null,
                    'address4'                => $row[7] ?? null,
                    'address5'                => $row[8] ?? null,
                    'address6'                => $row[9] ?? null,
                    'mobile'                  => $row[10] ?? null,
                    'next_of_kin'             => $row[11] ?? null,
                    'nok_phone'               => $row[12] ?? null,
                    'nok_alt_phone'           => $row[13] ?? null,
                    'dob'                     => $dob,
                    'allergies'               => $row[15] ?? null,
                    'dietary_requirement'     => $row[16] ?? null,
                    'past_medical_history'    => $row[17] ?? null,
                    'current_medical_history' => $row[18] ?? null,
                    'current_medications'     => $row[19] ?? null,
                    'vehicle_image'           => $row[20] ?? null,
                    'comments'                => $row[21] ?? null,
                    'destroy_date'            => $destroyDate
                ]);
            }

            fclose($handle);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'CSV imported successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'An error occurred during import: ' . $e->getMessage() . ' at line ' . $e->getLine()
            ], 500);
        }
    }
}
