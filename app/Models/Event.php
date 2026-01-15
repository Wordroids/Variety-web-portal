<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;
    protected $fillable = [
        "title",
        "description",
        "start_date",
        "end_date",
        "sponsor_image_path",
        "cover_image_path",
    ];

    protected $casts = [
        "start_date" => "date",
        "end_date" => "date",
    ];
    public function days()
    {
        return $this->hasMany(EventDay::class)->orderBy("sort_order");
    }

    public function participants()
    {
        return $this->hasMany(EventParticipant::class);
    }

    public function admins()
    {
        return $this->belongsToMany(
            User::class,
            "event_admin",
            "event_id",
            "user_id",
        )->withTimestamps();
    }

    public function isAdmin(User $user)
    {
        if ($user->hasRole("Super Admin")) {
            return true;
        }

        return $this->admins()->where("user_id", $user->id)->exists();
    }

    /**
     * Notificaions
     */
    public function notifications()
    {
        return $this->belongsToMany(Notification::class, "notification_event");
    }
}
