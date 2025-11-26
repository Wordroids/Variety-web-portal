<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Runs before any other method
     */
    public function before(User $user, $ability): ?bool
    {
        if ($user->hasRole('Super Admin')) {
            return true; 
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can('view events');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create events');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can('edit events');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can('delete events');
    }

    /**
     * Determine whether the user can import participants to the event
     * from excel file.
     */
    public function importParticipant(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can('manage participants');
    }

    /**
     * Determine whether the user can add participants to the event.
     */
    public function createParticipant(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can('manage participants');
    }

    /**
     * Determine whether the user can update participants in the event.
     */
    public function updateParticipant(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can('manage participants');
    }

    /**
     * Determine whether the user can remove participants from the event.
     */
    public function deleteParticipant(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can('manage participants');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return false;
    }
}
