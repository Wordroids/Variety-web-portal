<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;

class EventFormController extends Controller
{
    public function index(Event $event)
    {
        return response()->json([
            "success" => true,
            "forms" => $event->forms,
        ]);
    }
}
