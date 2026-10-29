<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\MedicalRecordComment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MedicalRecordCommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "medical_record_id" => "required|exists:medical_records,id",
            "comment" => "required",
        ]);

        $medicalRecord = MedicalRecord::findOrFail($request->medical_record_id);

        $this->authorize("create", [
            MedicalRecordComment::class,
            $medicalRecord,
        ]);

        MedicalRecordComment::create([
            "medical_record_id" => $request->medical_record_id,
            "content" => $request->comment,
        ]);

        return back()->with("success", "Comment added");
    }

    /**
     * Delete the resource in storage.
     */
    public function destroy(int $id)
    {
        $comment = MedicalRecordComment::findOrFail($id);

        $this->authorize("delete", $comment);

        $comment->delete();
        return back()->with("success", "Comment deleted");
    }
}
