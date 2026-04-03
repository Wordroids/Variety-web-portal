<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function Index(Event $event)
    {
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
