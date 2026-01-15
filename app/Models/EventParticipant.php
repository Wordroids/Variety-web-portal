<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class EventParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        "event_id",
        "first_name",
        "last_name",
        "email",
        "phone",
        "vehicle",
        "status",
        "emergency_contact_name",
        "emergency_contact_phone",
        "emergency_contact_relationship",
        "username",
    ];

    protected $with = ["roles"];

    public function participant()
    {
        return $this->hasOne(Participant::class, "phone", "phone");
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, "event_participant_role");
    }

    public function notifications()
    {
        return $this->belongsToMany(
            Notification::class,
            "notification_event_participant",
        );
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Get the participant's role names as a comma-separated string
     */
    public function getRoleNamesAttribute(): string
    {
        return $this->roles->pluck("name")->implode(", ");
    }

    /**
     * Scope to exclude admin/superadmin roles
     */
    public function scopeExcludeAdminRoles($query)
    {
        return $query->whereDoesntHave("roles", function ($q) {
            $q->whereIn("name", ["Super Admin", "Administrator"]);
        });
    }
}
