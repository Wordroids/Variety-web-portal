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
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions from the export
        $permissions = [
            'view events',
            'create events',
            'edit events',
            'delete events',
            'manage participants',
            'edit users',
            'update profile',
            'create event participants',
            'update users',
            'update events',
            'manage settings'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles with their exact permissions from the export
        $roles = [
            'Super Admin' => [
                'view events', 'create events', 'edit events', 'delete events',
                'manage participants', 'edit users', 'update profile',
                'create event participants', 'update users', 'update events',
                'manage settings'
            ],
            'Event Manager' => [
                'view events', 'create events', 'edit events', 'manage participants'
            ],
            'Viewer' => ['view events'],
            'Administrator' => [
                'view events', 'create events', 'edit events', 'update profile',
                'create event participants', 'update users', 'update events'
            ],
            'Official' => [
                'view events', 'create events', 'edit events', 'delete events',
                'manage participants', 'edit users', 'update profile',
                'update users', 'update events'
            ],
            'Medic' => [
                'view events', 'create events', 'edit events', 'delete events',
                'manage participants', 'edit users', 'update profile',
                'create event participants', 'update users', 'update events'
            ]
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($perms);
        }
    }
}
