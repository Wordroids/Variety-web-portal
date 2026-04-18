<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MedicalRecordImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event, MedicalRecord $record)
    {
        if (Auth::user()->cannot("view medical images")) {
            abort(403, "Forbidden");
        }

        return response()->json([
            "success" => true,
            "message" => "Images listed.",
            "images" => $record->images,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Event $event, MedicalRecord $record)
    {
        if (Auth::user()->cannot("manage medical images")) {
            abort(403, "Forbidden");
        }

        $request->validate([
            "image" => "required|image|mimes:jpeg,png,jpg|max:10240", // Limit to images, max 10MB
        ]);

        $file = $request->file("image");
        $mime = $file->getMimeType();

        $fileName = Str::uuid();
        $path = "medical-records/images/$fileName";

        $encryptedContent = Crypt::encrypt(
            file_get_contents($file->getRealPath()),
        );

        Storage::disk("private")->put($path, $encryptedContent);

        $image = MedicalRecordImage::create([
            "medical_record_id" => $record->id,
            "path" => $path,
            "mime" => $mime,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Image uploaded.",
            "image" => $image,
        ]);
    }

    /**
     * Delete the resource in storage.
     */
    public function destroy(
        Request $request,
        Event $event,
        MedicalRecord $record,
        MedicalRecordImage $image,
    ) {
        if (Auth::user()->cannot("manage medical images")) {
            abort(403, "Forbidden");
        }

        $image->delete();
        return response()->json([
            "success" => true,
            "message" => "Image deleted.",
        ]);
    }
}
