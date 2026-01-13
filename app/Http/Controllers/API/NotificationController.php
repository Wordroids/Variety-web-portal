<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        return response()->json([
            "success" => true,
            "notifications" => auth()->user()->notifications(),
        ]);
    }

    public function token(Request $request)
    {
        $request->validate([
            "token" => "required|string",
        ]);

        auth()
            ->user()
            ->update([
                "push_token" => $request->token,
            ]);

        return response()->json([
            "success" => true,
            "message" => "Token saved successfully",
        ]);
    }
}
