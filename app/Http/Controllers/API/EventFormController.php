<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventFormController extends Controller
{
    public function index(Event $event)
    {
        if (Auth::user()->cannot("view forms")) {
            abort(403, "Forbidden");
        }

        return response()->json([
            "success" => true,
            "message" => "Forms listed.",
            "forms" => $event->forms,
        ]);
    }
}
