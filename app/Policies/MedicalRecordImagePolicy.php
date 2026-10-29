<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\MedicalRecordImage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MedicalRecordImagePolicy
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
        return $user->can("view medical records");
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MedicalRecordImage $image): bool
    {
        return $image->medicalRecord->event->isAdmin($user) && $user->can("view medical records");
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, MedicalRecord $medicalRecord): bool
    {
        return $medicalRecord->event->isAdmin($user) && $user->can("create medical record images");
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MedicalRecordImage $image): bool
    {
        return $image->medicalRecord->event->isAdmin($user) && $user->can("edit medical record images");
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MedicalRecordImage $image): bool
    {
        return $image->medicalRecord->event->isAdmin($user) && $user->can("delete medical record images");
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MedicalRecordImage $image): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MedicalRecordImage $image): bool
    {
        return false;
    }
}
