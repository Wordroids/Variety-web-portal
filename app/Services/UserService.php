<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function create(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'username'     => $data['username'],
                'name'         => $data['username'] ?? null,
                'first_name'   => $data['first_name'] ?? null,
                'last_name'    => $data['last_name'] ?? null,
                'email'        => $data['email'],
                'phone'        => $data['phone'] ?? null,
                'status'       => $data['status'],
                'vehicle_code' => $data['vehicle_code'] ?? null,
                'password'     => Hash::make($data['password']),
            ]);

            if (! empty($data['roles'])) {
                $user->syncRoles($data['roles']);
            }

            $user->assignedEvents()->sync($data['assigned_events'] ?? []);
            foreach ($data['assigned_events'] ?? [] as $eventId) {
                $event = \App\Models\Event::find($eventId);
                if ($event) {
                    $event->admins()->syncWithoutDetaching($user->id);
                }
            }

            return $user;
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $payload = [
                'username'     => $data['username'],
                'first_name'   => $data['first_name'] ?? null,
                'last_name'    => $data['last_name'] ?? null,
                'email'        => $data['email'],
                'phone'        => $data['phone'] ?? null,
                'status'       => $data['status'],
                'vehicle_code' => $data['vehicle_code'] ?? null,
            ];

            if (! empty($data['password'])) {
                $payload['password'] = Hash::make($data['password']);
            }

            $user->update($payload);

            if (array_key_exists('roles', $data)) {
                $user->syncRoles($data['roles'] ?? []);
            }

            if (array_key_exists('assigned_events', $data)) {

                $eventIds = $data['assigned_events'] ?? [];

                // Sync user → events
                $user->assignedEvents()->sync($eventIds);

                // Sync each event → admins (inverse relationship)
                foreach ($eventIds as $eventId) {
                    Event::find($eventId)?->admins()->syncWithoutDetaching([$user->id]);
                }
            } else {

                // No assigned_events key → remove all
                $user->assignedEvents()->detach();

                // Remove from event_admin pivot fully
                foreach ($user->assignedEvents as $event) {
                    $event->admins()->detach($user->id);
                }
            }

            return $user;
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->assignedEvents()->detach();
            $user->syncRoles([]);
            $user->delete();
        });
    }
}
