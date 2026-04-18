<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache
        app()[
            \Spatie\Permission\PermissionRegistrar::class
        ]->forgetCachedPermissions();

        // Define all permissions from the export
        $permissions = [
            // Events
            "view events",
            "create events",
            "edit events",
            "delete events",
            "update events",

            // Participants
            "view participants",
            "view participants limited",
            "manage participants",

            // Medical Records
            "view medical records",
            "manage medical records",
            "view medical images",
            "manage medical images",
            "view medical comments",
            "manage medical comments",

            // Jobs
            "view jobs",
            "manage jobs",

            // Permits
            "view permits",
            "manage permits",

            // Forms
            "view forms",
            "manage forms",

            // Forms
            "view notifications",
            "manage notifications",

            // Users
            "edit users",
            "update users",
            "update profile",

            // Settings
            "manage settings",
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(["name" => $permission]);
        }

        // Define roles with their exact permissions from the export
        $roles = [
            "Super Admin" => [
                "view events",
                "create events",
                "edit events",
                "delete events",
                "update events",
                "view participants",
                "view participants limited",
                "manage participants",
                "view medical records",
                "manage medical records",
                "view medical images",
                "manage medical images",
                "view medical comments",
                "manage medical comments",
                "view jobs",
                "manage jobs",
                "view permits",
                "manage permits",
                "view forms",
                "manage forms",
                "view notifications",
                "manage notifications",
                "edit users",
                "update users",
                "update profile",
                "manage settings",
            ],
            "Event Manager" => [
                "view events",
                "create events",
                "edit events",
                "delete events",
                "update events",
                "view participants",
                "manage participants",
                "view notifications",
                "manage notifications",
                "update profile",
            ],
            "Viewer" => [
                "view events",
                "view participants limited",
                "view notifications",
                "update profile",
            ],
            "Administrator" => [
                "view events",
                "create events",
                "edit events",
                "delete events",
                "update events",
                "view participants",
                "view participants limited",
                "manage participants",
                "view medical records",
                "manage medical records",
                "view medical images",
                "manage medical images",
                "view medical comments",
                "manage medical comments",
                "view jobs",
                "manage jobs",
                "view permits",
                "manage permits",
                "view forms",
                "manage forms",
                "view notifications",
                "manage notifications",
                "update profile",
            ],
            "Official" => [
                "view events",
                "view participants",
                "view medical records",
                "view medical images",
                "manage medical images",
                "view medical comments",
                "manage medical comments",
                "view jobs",
                "view permits",
                "manage permits",
                "view forms",
                "update profile",
                "view notifications",
            ],
            "Medic" => [
                "view events",
                "view participants limited",
                "view medical records",
                "manage medical records",
                "view medical images",
                "manage medical images",
                "view medical comments",
                "manage medical comments",
                "view notifications",
                "update profile",
            ],
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(["name" => $roleName]);
            $role->syncPermissions($perms);
        }
    }
}
