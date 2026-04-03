<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Crypt;

class MedicalRecordImage extends Model
{
    protected $fillable = ["medical_record_id", "path", "mime"];
    protected $appends = ["image"];

    public function getImageAttribute()
    {
        $encryptedContent = Storage::disk("private")->get($this->path);
        $decryptedContent = Crypt::decrypt($encryptedContent);
        $base64Image = base64_encode($decryptedContent);
        return "data:{$this->mime};base64,{$base64Image}";
    }

    public function record(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    protected static function booted()
    {
        static::deleted(function ($image) {
            // Ensure the physical encrypted file is removed from the private disk
            Storage::disk("private")->delete($image->path);
        });
    }
}
