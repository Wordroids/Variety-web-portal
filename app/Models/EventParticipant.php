<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

final class EventParticipant extends Model
{
    use HasFactory, HasApiTokens;

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
        "password",
    ];

    protected $with = ["roles"];

    protected static function booted(): void
    {
        static::saving(function (self $participant): void {
            if ($participant->isDirty("password") && $participant->password) {
                $participant->password = Hash::make($participant->password);
            }
        });
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, "event_participant_role");
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
