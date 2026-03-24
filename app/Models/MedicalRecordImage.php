<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecordImage extends Model
{
    protected $fillable = ["record_id", "path"];

    public function record(): BelongsTo
    {
        return $this->belongsTo(MedicalRecordItem::class, "record_id");
    }

    protected static function booted()
    {
        static::deleted(function ($image) {
            // Ensure the physical encrypted file is removed from the private disk
            Storage::disk("private")->delete($image->path);
        });
    }
}
