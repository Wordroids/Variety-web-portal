<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'title',
        'message',
        'target_type',
        'status',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'notification_event');
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'notification_role');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user');
    }
}
