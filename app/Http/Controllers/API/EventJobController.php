<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventJobController extends Controller
{
    public function index(Event $event)
    {
        if (Auth::user()->cannot("view jobs")) {
            abort(403, "Forbidden");
        }

        return response()->json([
            "success" => true,
            "message" => "Jobs listed.",
            "jobs" => $event->jobs()->get()->keyBy("id"),
        ]);
    }
}
