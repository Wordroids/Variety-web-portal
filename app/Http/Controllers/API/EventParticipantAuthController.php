<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\EventParticipantLoginRequest;
use Illuminate\Http\JsonResponse;

class EventParticipantAuthController extends Controller
{
    /**
     * Handle an incoming event participant authentication request.
     */
    public function login(EventParticipantLoginRequest $request): JsonResponse
    {
        $request->authenticate();
        $participant = $request->input("authenticated_participant");

        // Find all events this participant (by phone number) has access to
        $allParticipants = \App\Models\EventParticipant::with([
            "event",
            "roles",
        ])
            ->where("phone", $participant->phone)
            ->get();

        $records = [];
        foreach ($allParticipants as $p) {
            if ($p->event) {
                $records[] = $p;
            }
        }

        // Create a token for the participant (we'll use the participant's ID as the tokenable ID)
        $token = $participant->createToken("Participant API Token")
            ->plainTextToken;

        return response()->json([
            "token" => $token,
            "events" => $records,
            "message" => "Login successful",
        ]);
    }
}
