<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    protected $fillable = ["name", "guard_name"];

    public function passwords()
    {
        return $this->hasMany(Password::class);
    }
}
