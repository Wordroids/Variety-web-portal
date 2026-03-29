<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecordComment;
use Illuminate\Http\Request;

class MedicalRecordCommentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "medical_record_id" => "required|exists:medical_records,id",
            "comment" => "required",
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
        MedicalRecordComment::findOrFail($id)->delete();
        return back()->with("success", "Comment deleted");
    }
}
