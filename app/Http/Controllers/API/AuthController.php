<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ParticipantLoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Handle an incoming participant authentication request.
     */
    public function login(ParticipantLoginRequest $request): JsonResponse
    {
        $request->authenticate();
        $participant = $request->authenticated_participant;

        // Create a token for the participant
        $token = $participant->createToken("Participant API Token")
            ->plainTextToken;

        return response()->json([
            "token" => $token,
            "participant" => $participant,
            "message" => "Login successful",
        ]);
    }

    /**
     * Handle participant logout.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            "message" => "Logout successful",
        ]);
    }

    /**
     * Get the authenticated participant's profile.
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            "participant" => $request->user(),
            "message" => "Profile shown",
        ]);
    }
}
