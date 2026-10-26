<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventPermit;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EventPermitController extends Controller
{
    use AuthorizesRequests;

    public function index(Event $event)
    {
        $this->authorize("viewAny", [EventPermit::class, $event]);

        $permits = $event->permits;
        return view("pages.events.permits.index", compact("event", "permits"));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize("create", [EventPermit::class, $event]);

        $request->validate([
            "title" => "required",
            "file" => "required|file",
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
        $this->authorize("delete", $permit);

        $permit->delete();
        return back()->with("success", "Permit Deleted");
    }
}
