<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPermit;
use Illuminate\Http\Request;

class EventPermitController extends Controller
{
    public function index(Event $event)
    {
        return response()->json([
            "success" => true,
            "message" => "Permits listed.",
            "permits" => $event->permits,
        ]);
    }

    public function store(Request $request, Event $event)
    {
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

        return response()->json([
            "success" => true,
            "message" => "Permit added.",
            "permit" => $eventPermit,
        ]);
    }
}
