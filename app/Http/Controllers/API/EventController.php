<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // Check if the user is a participant
        if (!$user instanceof Participant) {
            return response()->json(
                [
                    "message" => "Only participants can access this resource.",
                ],
                403,
            );
        }

        $events = [];

        // Get all events that the user is a participant of
        foreach ($user->eventParticipants()->get() as $eventParticipant) {
            $event = $eventParticipant
                ->event()
                ->with([
                    "participants",
                    "participants.roles",
                    "days",
                    "days.locations",
                    "days.resources",
                ])
                ->first();

            // Append the rendered itinerary description to each day
            $event->days->each(function ($day) {
                $day->append("itinerary_description_html");
            });

            $events[$event->id] = [
                "me" => $eventParticipant,
                "event" => $event,
            ];
        }

        return response()->json([
            "events" => $events,
            "message" => "Events listed.",
        ]);
    }
}
