<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventForm;
use Illuminate\Http\Request;

class EventFormController extends Controller
{
    public function index(Event $event)
    {
        $forms = $event->forms;
        return view("pages.events.forms.index", compact("event", "forms"));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            "title" => "required",
            "description" => "required",
            "link" => "required",
        ]);

        EventForm::create([
            "event_id" => $event->id,
            "title" => $request->title,
            "description" => $request->description,
            "link" => $request->link,
        ]);

        return back()->with("success", "Form added");
    }

    public function destroy(Event $event, EventForm $form)
    {
        $form->delete();
        return back()->with("success", "Form deleted");
    }
}
