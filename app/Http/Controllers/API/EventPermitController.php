<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
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
}
