<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecordCollection extends Model
{
    protected $fillable = ["event_id", "imported_at", "expires_at"];

    protected $casts = [
        "imported_at" => "date",
        "expires_at" => "date",
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function records(): HasMany
    {
        return $this->hasMany(MedicalRecordItem::class, "collection_id");
    }
}
