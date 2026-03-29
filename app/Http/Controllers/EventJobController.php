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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
