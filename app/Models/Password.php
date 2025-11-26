<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Password extends Model
{
    protected $fillable = ['role_id', 'password'];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
