<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RolesAndPermissionsSeeder::class]);

        // Create super admin user
        $superAdmin = User::factory()->create([
            "username" => "admin",
            "name" => "Super Admin",
            "email" => "admin@example.com",
            "password" => Hash::make("password"),
        ]);

        $superAdmin->assignRole("Super Admin");
    }
}
