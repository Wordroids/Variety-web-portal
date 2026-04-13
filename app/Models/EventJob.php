<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventJob extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "event_id",
        "event_day",
        "vehicle",
        "duty_code",
        "duty_description",
        "location",
        "period",
        "km",
        "ov_arrive",
        "field_arrive",
        "ov_departure",
        "comment",
        "image_path",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        "event_day" => "integer",
        "km" => "decimal:2",
        "ov_arrive" => "datetime:H:i",
        "field_arrive" => "datetime:H:i",
        "ov_departure" => "datetime:H:i",
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Value for HTML time inputs (H:i). Handles Carbon casts and raw DB time strings.
     */
    public function timeForInput(string $attribute): string
    {
        $value = $this->getAttribute($attribute);

        if ($value === null) {
            return "";
        }

        if ($value instanceof CarbonInterface) {
            return $value->format("H:i");
        }

        if (is_string($value) && preg_match("/^\d{1,2}:\d{2}/", $value)) {
            return substr($value, 0, 5);
        }

        return "";
    }
}
