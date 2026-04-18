<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event, MedicalRecord $record)
    {
        if (Auth::user()->cannot("view medical comments")) {
            abort(403, "Forbidden");
        }

        return response()->json([
            "success" => true,
            "message" => "Comments listed.",
            "comments" => $record->comments,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event, MedicalRecord $record)
    {
        if (Auth::user()->cannot("manage medical comments")) {
            abort(403, "Forbidden");
        }

        $request->validate([
            "comment" => "required",
        ]);

        $comment = MedicalRecordComment::create([
            "medical_record_id" => $record->id,
            "content" => $request->comment,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Comment added.",
            "comment" => $comment,
        ]);
    }

    /**
     * Delete the resource in storage.
     */
    public function destroy(
        Request $request,
        Event $event,
        MedicalRecord $record,
        MedicalRecordComment $comment,
    ) {
        if (Auth::user()->cannot("manage medical comments")) {
            abort(403, "Forbidden");
        }

        $comment->delete();
        return response()->json([
            "success" => true,
            "message" => "Comment deleted.",
        ]);
    }
}
