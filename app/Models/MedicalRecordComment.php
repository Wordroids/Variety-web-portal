<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecordComment extends Model
{
    protected $fillable = ["record_id", "content"];

    protected $casts = [
        "content" => "encrypted", // Encrypts the string content
    ];

    public function record(): BelongsTo
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
