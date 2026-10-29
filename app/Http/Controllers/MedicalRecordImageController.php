<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Models\MedicalRecordImage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicalRecordImageController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Precise Validation
        $request->validate([
            "medical_record_id" => "required|exists:medical_records,id",
            "image" => "required|image|mimes:jpeg,png,jpg|max:10240", // Limit to images, max 10MB
        ]);

        $medicalRecord = MedicalRecord::findOrFail($request->medical_record_id);

        $this->authorize("create", [
            MedicalRecordImage::class,
            $medicalRecord,
        ]);

        $file = $request->file("image");
        $mime = $file->getMimeType();

        $fileName = Str::uuid();
        $path = "medical-records/images/$fileName";

        $encryptedContent = Crypt::encrypt(
            file_get_contents($file->getRealPath()),
        );

        Storage::disk("private")->put($path, $encryptedContent);

        MedicalRecordImage::create([
            "medical_record_id" => $request->medical_record_id,
            "path" => $path,
            "mime" => $mime,
        ]);

        return back()->with("success", "Image uploaded.");
    }

    /**
     * Delete the resource in storage.
     */
    public function destroy(int $id)
    {
        $image = MedicalRecordImage::findOrFail($id);

        $this->authorize("delete", $image);

        $image->delete();
        return back()->with("success", "Image deleted");
    }
}
