<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $user = $request->user();
        $token = $user->createToken("API Token")->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $token,
            "message" => "Login successful",
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the token that was used to authenticate the current request
        $user = $request->user();
        if ($user && method_exists($user->currentAccessToken(), "delete")) {
            $user->currentAccessToken()->delete();
        }

        return response()->json([
            "message" => "Logout successful",
        ]);
    }

    /**
     * Get the authenticated user.
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            "user" => $request->user(),
            "message" => "Profile shown",
        ]);
    }
}
