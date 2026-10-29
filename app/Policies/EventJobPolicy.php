<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\EventJob;
use App\Models\User;

class EventJobPolicy
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
    public function viewAny(User $user): bool
    {
        return $user->can("view jobs");
    }

    /**
     * Determine whether the user can view jobs for an event.
     */
    public function view(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("view jobs");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("create jobs");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventJob $eventJob): bool
    {
        return $eventJob->event->isAdmin($user) && $user->can("edit jobs");
    }

    /**
     * Determine whether the user can delete jobs for an event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("delete jobs");
    }

    /**
     * Determine whether the user can export the template.
     */
    public function exportTemplate(User $user): bool
    {
        return $user->can("export job template");
    }

    /**
     * Determine whether the user can upload images.
     */
    public function uploadImage(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("create job images");
    }

    /**
     * Determine whether the user can remove images.
     */
    public function removeImage(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("delete job images");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventJob $eventJob): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventJob $eventJob): bool
    {
        return false;
    }
}
