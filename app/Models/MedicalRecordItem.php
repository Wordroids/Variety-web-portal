<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecordItem extends Model
{
    protected $fillable = ["collection_id", "participant_id", "content"];

    protected $casts = [
        "content" => "encrypted:json", // Automatically encrypts/decrypts the JSON blob
    ];

    public function collection(): BelongsTo
    {
        return $this->belongsTo(MedicalRecordCollection::class);
    }

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(MedicalRecordImage::class, "record_id");
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MedicalRecordComment::class, "record_id");
    }
}
