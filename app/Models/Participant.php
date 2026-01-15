<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Notification as ModelsNotification;
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

    protected $fillable = ["phone", "push_token"];

    protected $hidden = ["remember_token"];

    /**
     * Get all event participants for this participant
     */
    public function eventParticipants()
    {
        return $this->hasMany(EventParticipant::class, "phone", "phone");
    }

    /**
     * Get all events this participant is registered for
     */
    public function events()
    {
        return $this->hasManyThrough(
            Event::class,
            EventParticipant::class,
            "phone", // Foreign key on EventParticipant table
            "id", // Foreign key on Event table
            "phone", // Local key on Participant table
            "event_id", // Local key on EventParticipant table
        );
    }

    /**
     * Find participant by phone number
     */
    public static function findByPhone(string $phone): ?self
    {
        return self::where("phone", $phone)->first();
    }

    /**
     * Get all notifications for this participant
     * This includes notifications targeted directly at the participant,
     * notifications targeted at events the participant is registered for,
     * and notifications targeted at roles the participant has in specific events.
     */
    public function notifications()
    {
        $notifications = collect();

        $this->eventParticipants()->each(function ($eventParticipant) use (
            $notifications,
        ) {
            // Direct participant notifications
            $eventParticipant->notifications->each(
                fn($n) => $notifications->put($n->id, $n),
            );

            // Event-level notifications
            $eventParticipant->event->notifications
                ->where("target_type", "event")
                ->each(fn($n) => $notifications->put($n->id, $n));

            // Role-based notifications scoped to event
            $eventParticipant
                ->roles()
                ->each(function ($role) use (
                    $notifications,
                    $eventParticipant,
                ) {
                    $role
                        ->notifications()
                        ->where("target_type", "role")
                        ->whereHas(
                            "events",
                            fn($q) => $q->where(
                                "events.id",
                                $eventParticipant->event_id,
                            ),
                        )
                        ->each(fn($n) => $notifications->put($n->id, $n));
                });
        });

        return $notifications->values();
    }
}
