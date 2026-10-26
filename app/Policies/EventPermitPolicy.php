<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\EventPermit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPermitPolicy
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
        return $event->isAdmin($user) && $user->can("view event permits");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, EventPermit $eventPermit): bool
    {
        return $eventPermit->event->isAdmin($user) &&
            $user->can("view event permits");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("create event permits");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, EventPermit $eventPermit): bool
    {
        return $eventPermit->event->isAdmin($user) &&
            $user->can("edit event permits");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, EventPermit $eventPermit): bool
    {
        return $eventPermit->event->isAdmin($user) &&
            $user->can("delete event permits");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, EventPermit $eventPermit): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, EventPermit $eventPermit): bool
    {
        return false;
    }
}
