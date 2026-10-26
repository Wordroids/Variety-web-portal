<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventForm;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventFormController extends Controller
{
    use AuthorizesRequests;

    public function index(Event $event)
    {
        $this->authorize("viewAny", [EventForm::class, $event]);

        $forms = $event->forms;
        return view("pages.events.forms.index", compact("event", "forms"));
    }

    public function store(Request $request, Event $event)
    {
        $this->authorize("create", [EventForm::class, $event]);

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
        $this->authorize("delete", $form);

        $form->delete();
        return back()->with("success", "Form deleted");
    }
}
