<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPermit;
use Illuminate\Http\Request;

class EventPermitController extends Controller
{
    public function index(Event $event)
    {
        $permits = $event->permits;
        return view("pages.events.permits.index", compact("event", "permits"));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            "title" => "required",
            "file" => "file",
        ]);

        $eventPermit = EventPermit::create([
            "event_id" => $event->id,
            "title" => $request->title,
            "filename" => $request->file->getClientOriginalName(),
            "path" => $request->file->store("events/permits", "public"),
            "uploaded_at" => now(),
        ]);

        return back()->with("success", "Permit Uploaded");
    }

    public function destroy(Event $event, EventPermit $permit)
    {
        $permit->delete();
        return back()->with("success", "Permit Deleted");
    }
}
