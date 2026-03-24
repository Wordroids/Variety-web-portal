<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventForm extends Model
{
    protected $fillable = ["event_id", "title", "description", "link"];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
