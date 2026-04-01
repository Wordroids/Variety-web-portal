<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventJob;
use Illuminate\Http\Request;

class EventJobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::query()->withCount("jobs")->orderByDesc("id")->get();

        return view("pages.jobs.index", compact("events"));
    }

    public function store(Request $request)
    {
        $request->validate([
            "event_id" => "required|exists:events,id",
            "file" => "required|file|mimes:csv,txt", // Added mime validation for safety
        ]);

        $file = $request->file("file");

        // Open the file for reading
        if (($handle = fopen($file->getRealPath(), "r")) !== false) {
            // Extract the header row
            $header = fgetcsv($handle, 1000, ",");

            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                // Combine header with row data to create an associative array
                $jobRow = array_combine($header, $row);

                $job = EventJob::create([
                    "event_id" => $request->event_id,
                    "event_day" => $jobRow["Event Day"],
                    "vehicle" => $jobRow["Vehicle"],
                    "duty_code" => $jobRow["Duty Code"],
                    "duty_description" => $jobRow["Duty Description"],
                    "location" => $jobRow["Location"],
                    "period" => $jobRow["AM/PM"],
                    "km" => $jobRow["KM"],
                    "ov_arrive" => !empty($jobRow["OV Arrive"])
                        ? $jobRow["OV Arrive"]
                        : null,
                    "field_arrive" => !empty($jobRow["Field Arrive"])
                        ? $jobRow["Field Arrive"]
                        : null,
                    "ov_departure" => !empty($jobRow["OV Departure"])
                        ? $jobRow["OV Departure"]
                        : null,
                    "comment" => $jobRow["Comment"] ?? null,
                    "image_path" => $jobRow["Image"] ?? null,
                ]);
            }
            fclose($handle);
        }

        return back()->with("success", "CSV imported successfully");
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Note: Using permits() as per your original logic
        $jobs = $event->jobs()->latest()->get();
        $events = Event::query()->withCount("jobs")->orderByDesc("id")->get();

        return view("pages.jobs.view", compact("events", "event", "jobs"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $event->jobs()->delete();

        return redirect()
            ->route("jobs.view", $event)
            ->with("success", "Jobs deleted successfully.");
    }
}
