<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\JobImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class JobImagePolicy
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
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("view job images");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, JobImage $jobImage): bool
    {
        return $jobImage->event->isAdmin($user) && $user->can("view job images");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("create job images");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, JobImage $jobImage): bool
    {
        return $jobImage->event->isAdmin($user) && $user->can("edit job images");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, JobImage $jobImage): bool
    {
        return $jobImage->event->isAdmin($user) && $user->can("delete job images");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, JobImage $jobImage): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, JobImage $jobImage): bool
    {
        return false;
    }
}
