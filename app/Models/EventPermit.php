<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EventPermit extends Model
{
    protected $fillable = [
        "event_id",
        "title",
        "filename",
        "path",
        "uploaded_at",
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected static function booted()
    {
        static::deleted(function ($permit) {
            Storage::disk("public")->delete($permit->path);
        });
    }
}
