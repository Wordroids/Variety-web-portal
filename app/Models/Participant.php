<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Central participant model that links all event participants by phone number
 */
final class Participant extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = [
        "phone",
    ];

    protected $hidden = [
        'remember_token',
    ];

    /**
     * Get all event participants for this participant
     */
    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class, 'phone', 'phone');
    }

    /**
     * Get all events this participant is registered for
     */
    public function events()
    {
        return $this->hasManyThrough(
            Event::class,
            EventParticipant::class,
            'phone', // Foreign key on EventParticipant table
            'id',    // Foreign key on Event table
            'phone', // Local key on Participant table
            'event_id' // Local key on EventParticipant table
        );
    }

    /**
     * Find participant by phone number
     */
    public static function findByPhone(string $phone): ?self
    {
        return self::where('phone', $phone)->first();
    }
}
