<?php

namespace App\Policies;

use App\Models\User;

class AttachmentPolicy
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
     * Determine whether the user can create attachments.
     */
    public function create(User $user): bool
    {
        return $user->can("create attachments");
    }

    /**
     * Determine whether the user can delete the attachment.
     */
    public function delete(User $user): bool
    {
        return $user->can("create attachments");
    }
}
