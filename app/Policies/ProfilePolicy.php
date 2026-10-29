<?php

namespace App\Policies;

use App\Models\User;

class ProfilePolicy
{
    /**
     * Runs before any other method
     */
    public function before(User $user, $ability): ?bool
    {
        if ($user->hasRole("Super Admin")) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view their profile.
     */
    public function view(User $user, User $profile): bool
    {
        return $user->id === $profile->id || $user->can("view users");
    }

    /**
     * Determine whether the user can update their profile.
     */
    public function update(User $user, User $profile): bool
    {
        return $user->id === $profile->id || $user->can("update profile");
    }

    /**
     * Determine whether the user can delete their profile.
     */
    public function delete(User $user, User $profile): bool
    {
        return ($user->id === $profile->id && $user->can("delete profile")) ||
            $user->can("delete users");
    }
}
