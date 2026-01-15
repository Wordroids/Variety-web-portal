<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        "title",
        "message",
        "target_type",
        "status",
        "scheduled_at",
        "sent_at",
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, "notification_event");
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, "notification_role");
    }

    public function eventParticipants()
    {
        return $this->belongsToMany(
            EventParticipant::class,
            "notification_event_participant",
        );
    }

    public function participants()
    {
        $participants = collect();

        if ($this->target_type === "event") {
            $this->events->each(function ($event) use ($participants) {
                $event->participants->flatMap(function ($ep) use (
                    $participants,
                ) {
                    if ($ep->participant) {
                        $participants->push($ep->participant);
                    }
                });
            });
        } elseif ($this->target_type === "role") {
            $this->roles->each(function ($role) use ($participants) {
                $this->events
                    ->first()
                    ->participants->each(function ($ep) use (
                        $participants,
                        $role,
                    ) {
                        if (
                            $ep
                                ->roles()
                                ->where("role_id", $role->id)
                                ->exists() &&
                            $ep->participant
                        ) {
                            $participants->push($ep->participant);
                        }
                    });
            });
        } elseif ($this->target_type === "participant") {
            $this->eventParticipants()->each(function ($ep) use (
                $participants,
            ) {
                if ($ep->participant) {
                    $participants->push($ep->participant);
                }
            });
        }

        return $participants->values();
    }
}
