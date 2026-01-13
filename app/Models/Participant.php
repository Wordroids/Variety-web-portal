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
        $notifications = [];

        // Notifications targeted at the participant directly
        $this->eventParticipants()->each(function ($eventParticipant) use (
            &$notifications,
        ) {
            if ($eventParticipant->notifications->count()) {
                $notifications = [
                    ...$notifications,
                    ...$eventParticipant->notifications->toArray(),
                ];
            }
        });

        // Notifications targeted at events the participant is registered for
        $this->eventParticipants()->each(function ($eventParticipant) use (
            &$notifications,
        ) {
            $notifications = [
                ...$notifications,
                ...$eventParticipant->event->notifications
                    ->where("target_type", "event")
                    ->toArray(),
            ];
        });

        // Notifications targeted at roles the participant has in events
        $this->eventParticipants()->each(function ($eventParticipant) use (
            &$notifications,
        ) {
            $event = $eventParticipant->event;
            $roleNotifications = [];
            $eventParticipant
                ->roles()
                ->each(function ($role) use (&$roleNotifications, &$event) {
                    $role
                        ->notifications()
                        ->each(function ($notification) use (
                            &$roleNotifications,
                            &$event,
                        ) {
                            if (
                                $notification->target_type === "role" &&
                                $notification->events->contains($event->id)
                            ) {
                                $roleNotifications = [
                                    ...$roleNotifications,
                                    $notification,
                                ];
                            }
                        });
                });
            $notifications = [...$notifications, ...$roleNotifications];
        });

        return $notifications;
    }
}
