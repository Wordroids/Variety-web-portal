<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventPermit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventPermitController extends Controller
{
    public function index(Event $event)
    {
        if (Auth::user()->cannot("view permits")) {
            abort(403);
        }

        return response()->json([
            "success" => true,
            "message" => "Permits listed.",
            "permits" => $event->permits,
        ]);
    }

    public function store(Request $request, Event $event)
    {
        if (Auth::user()->cannot("manage permits")) {
            abort(403);
        }

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

    public function destroy(Event $event, EventPermit $permit)
    {
        if (Auth::user()->cannot("manage permits")) {
            abort(403, "Forbidden");
        }

        $permit->delete();
        return response()->json([
            "success" => true,
            "message" => "Permit deleted.",
        ]);
    }
}
