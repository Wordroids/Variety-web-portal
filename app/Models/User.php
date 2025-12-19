<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        "username",
        "name",
        "first_name",
        "last_name",
        "email",
        "phone",
        "status",
        "vehicle_code",
        "password",
    ];

    protected $hidden = ["password", "remember_token"];

    protected $casts = [
        "email_verified_at" => "datetime",
    ];

    public function assignedEvents()
    {
        return $this->belongsToMany(
            Event::class,
            "event_admin",
            "user_id",
            "event_id",
        )->withTimestamps();
    }

    public function events()
    {
        return $this->belongsToMany(
            Event::class,
            "event_admin",
        )->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return trim(
            ($this->first_name ?? "") . " " . ($this->last_name ?? ""),
        ) ?:
            $this->username;
    }
}
