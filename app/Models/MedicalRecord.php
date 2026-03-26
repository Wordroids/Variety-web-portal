<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    protected $fillable = [
        "event_id",
        "participant_id",
        "content",
        "imported_at",
        "expires_at",
    ];

    protected $casts = [
        "content" => "encrypted:json", // Automatically encrypts/decrypts the JSON blob
        "imported_at" => "date",
        "expires_at" => "date",
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(EventParticipant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(MedicalRecordImage::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MedicalRecordComment::class);
    }
}
