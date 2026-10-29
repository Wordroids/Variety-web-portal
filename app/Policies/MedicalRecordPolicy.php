<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\MedicalRecord;
use App\Models\User;

class MedicalRecordPolicy
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
    public function viewAny(User $user, ?Event $event = null): bool
    {
        if ($event) {
            return $event->isAdmin($user) && $user->can("view medical records");
        }

        return $user->can("view medical records");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        return $medicalRecord->event->isAdmin($user) &&
            $user->can("view medical records");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("create medical records");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        return $medicalRecord->event->isAdmin($user) &&
            $user->can("edit medical records");
    }

    /**
     * Determine whether the user can delete medical records for an event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("delete medical records");
    }

    /**
     * Determine whether the user can export the template.
     */
    public function exportTemplate(User $user): bool
    {
        return $user->can("export medical record template");
    }

    /**
     * Determine whether the user can import medical records.
     */
    public function import(User $user, Event $event): bool
    {
        return $event->isAdmin($user) && $user->can("import medical records");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalRecord $medicalRecord): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalRecord $medicalRecord): bool
    {
        return false;
    }
}
