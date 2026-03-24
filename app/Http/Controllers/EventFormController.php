<?php

namespace App\Http\Controllers;

use App\Models\Event;

class EventFormController extends Controller
{
    public function index(Event $event)
    {
        return view('pages.events.forms.index', compact('event'));
    }
}
