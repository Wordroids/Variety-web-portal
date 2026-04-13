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
            'event_id' => 'required|exists:events,id',
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $event = Event::findOrFail($request->event_id);

        // Delete existing jobs
        EventJob::where('event_id', $event->id)->delete();

        $file = $request->file('csv_file');

        $handle = fopen($file->getPathname(), 'r');



        fgetcsv($handle);

        while (($row = fgetcsv($handle, 1000, ',')) !== false) {

            // convert encoding
            $row = array_map(function ($value) {

                if ($value === null) return null;

                // convert Windows-1252 to UTF-8
                $value = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');

               
                $value = iconv('UTF-8', 'UTF-8//IGNORE', $value);

                return trim($value);
            }, $row);

            EventJob::create([
                'event_id' => $event->id,
                'event_day' => $row[0] ?? null,
                'vehicle' => $row[1] ?? null,
                'duty_code' => $row[2] ?? null,
                'duty_description' => $row[3] ?? null,
                'location' => $row[4] ?? null,
                'period' => $row[5] ?? null,
                'km' => $row[6] ?? 0,
                'ov_arrive' => !empty($row[7]) ? $row[7] : null,
                'field_arrive' => !empty($row[8]) ? $row[8] : null,
                'ov_departure' => !empty($row[9]) ? $row[9] : null,
                'comment' => $row[10] ?? null,
                'image_path' => null,
            ]);
        }

        fclose($handle);

        return redirect()
            ->route('jobs.index')
            ->with('success', 'CSV imported successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Event $event)
    {
        $query = $event->jobs()->latest();

        // Filter by Vehicle
        if ($request->vehicle) {
            $query->where('vehicle', $request->vehicle);
        }

        // Filter by Event Day
        if ($request->event_day) {
            $query->where('event_day', $request->event_day);
        }

        // Filter by Period
        if ($request->period) {
            $query->where('period', $request->period);
        }

        $jobs = $query->paginate(10)->withQueryString();

        // dropdown values
        $vehicles = $event->jobs()->select('vehicle')->distinct()->pluck('vehicle');
        $eventDays = $event->jobs()->select('event_day')->distinct()->pluck('event_day');
        $periods = ['AM', 'PM'];

        $events = Event::query()
            ->withCount("jobs")
            ->orderByDesc("id")
            ->get();

        return view("pages.jobs.view", compact(
            "events",
            "event",
            "jobs",
            "vehicles",
            "eventDays",
            "periods"
        ));
    }

    //edit function
    public function edit(EventJob $job)
    {
        return view("pages.jobs.edit", compact("job"));
    }

    // update function
    public function update(Request $request, EventJob $job)
    {
        $request->validate([
            "event_day" => "required|integer",
            "vehicle" => "required",
            "duty_code" => "required",
            "duty_description" => "required",
            "location" => "required",
            "period" => "required",
            "km" => "required|numeric",
        ]);

        $job->update([
            "event_day" => $request->event_day,
            "vehicle" => $request->vehicle,
            "duty_code" => $request->duty_code,
            "duty_description" => $request->duty_description,
            "location" => $request->location,
            "period" => $request->period,
            "km" => $request->km,
            "ov_arrive" => $request->ov_arrive,
            "field_arrive" => $request->field_arrive,
            "ov_departure" => $request->ov_departure,
            "comment" => $request->comment,
        ]);

        return redirect()
            ->route("jobs.view", $job->event_id)
            ->with("success", "Job updated successfully");
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

    //to download the csv template
    public function downloadTemplate()
    {
        $fileName = "ov_jobs_template.csv";

        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $columns = [
            "event_day",
            "vehicle",
            "duty_code",
            "duty_description",
            "location",
            "period",
            "km",
            "ov_arrive",
            "field_arrive",
            "ov_departure",
            "comment"
        ];

        $callback = function () use ($columns) {

            $file = fopen('php://output', 'w');

            fputcsv($file, $columns);

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
