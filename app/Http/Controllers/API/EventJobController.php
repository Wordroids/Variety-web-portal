<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventJobController extends Controller
{
    public function index(Event $event)
    {
        return response()->json([
            "success" => true,
            "message" => "Jobs listed.",
            "jobs" => $event->jobs()->get()->keyBy("id"),
        ]);
    }
}
