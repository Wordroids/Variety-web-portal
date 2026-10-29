<?php

namespace App\Policies;

use App\Models\Settings;
use App\Models\User;

class SettingsPolicy
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
     * Determine whether the user can view the settings.
     */
    public function viewAny(User $user): bool
    {
        return $user->can("view settings");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Settings $settings): bool
    {
        return $user->can("view settings");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can("edit settings");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ?Settings $settings = null): bool
    {
        return $user->can("edit settings") || $user->can("manage settings");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Settings $settings): bool
    {
        return $user->can("manage settings");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Settings $settings): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Settings $settings): bool
    {
        return false;
    }
}
