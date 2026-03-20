<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventPermitController extends Controller
{
    public function index(Event $event)
    {
        return view('pages.events.permits.index', compact('event'));
    }
}
