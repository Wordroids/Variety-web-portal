<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    public function Index(Event $event)
    {
        if (Auth::user()->cannot("view medical records")) {
            abort(403, "Forbidden");
        }

        return response()->json([
            "success" => true,
            "message" => "Medical records listed",
            "records" => $event
                ->medicalRecords()
                ->get()
                ->keyBy("participant_id"),
        ]);
    }
}
