<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Password;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_specific_passwords_can_be_created()
    {
        // Create a role
        $role = Role::create(["name" => "Test Role"]);

        // Create an event
        $event = Event::create([
            "title" => "Test Event",
            "description" => "Test Description",
            "start_date" => now(),
            "end_date" => now()->addDay(),
        ]);

        // Create a password for the event and role
        $password = Password::create([
            "event_id" => $event->id,
            "role_id" => $role->id,
            "password" => "test-password-123",
        ]);

        // Assert the password was created
        $this->assertDatabaseHas("passwords", [
            "event_id" => $event->id,
            "role_id" => $role->id,
            "password" => "test-password-123",
        ]);

        // Assert the relationship works
        $this->assertEquals($event->id, $password->event->id);
        $this->assertEquals($role->id, $password->role->id);
    }

    public function test_passwords_are_unique_per_event_and_role()
    {
        // Create two events
        $event1 = Event::create([
            "title" => "Test Event 1",
            "description" => "Test Description 1",
            "start_date" => now(),
            "end_date" => now()->addDay(),
        ]);

        $event2 = Event::create([
            "title" => "Test Event 2",
            "description" => "Test Description 2",
            "start_date" => now(),
            "end_date" => now()->addDay(),
        ]);

        // Create a role
        $role = Role::create(["name" => "Shared Role"]);

        // Create passwords for both events with the same role
        $password1 = Password::create([
            "event_id" => $event1->id,
            "role_id" => $role->id,
            "password" => "password-for-event-1",
        ]);

        $password2 = Password::create([
            "event_id" => $event2->id,
            "role_id" => $role->id,
            "password" => "password-for-event-2",
        ]);

        // Assert both passwords exist
        $this->assertDatabaseHas("passwords", [
            "event_id" => $event1->id,
            "role_id" => $role->id,
            "password" => "password-for-event-1",
        ]);

        $this->assertDatabaseHas("passwords", [
            "event_id" => $event2->id,
            "role_id" => $role->id,
            "password" => "password-for-event-2",
        ]);

        // Assert they are different records
        $this->assertNotEquals($password1->id, $password2->id);
    }

    public function test_password_update_or_create_works_with_event()
    {
        // Create an event
        $event = Event::create([
            "title" => "Test Event",
            "description" => "Test Description",
            "start_date" => now(),
            "end_date" => now()->addDay(),
        ]);

        // Create a role
        $role = Role::create(["name" => "Test Role"]);

        // First create
        $password1 = Password::updateOrCreate(
            ["event_id" => $event->id, "role_id" => $role->id],
            ["password" => "initial-password"],
        );

        $this->assertEquals("initial-password", $password1->password);

        // Then update
        $password2 = Password::updateOrCreate(
            ["event_id" => $event->id, "role_id" => $role->id],
            ["password" => "updated-password"],
        );

        $this->assertEquals("updated-password", $password2->password);
        $this->assertEquals($password1->id, $password2->id); // Same record
    }

    public function test_unique_constraint_on_event_id_and_role_id()
    {
        // Create an event
        $event = Event::create([
            "title" => "Test Event",
            "description" => "Test Description",
            "start_date" => now(),
            "end_date" => now()->addDay(),
        ]);

        // Create a role
        $role = Role::create(["name" => "Test Role"]);

        // Create first password
        Password::create([
            "event_id" => $event->id,
            "role_id" => $role->id,
            "password" => "first-password",
        ]);

        // Try to create duplicate - should fail
        $this->expectException("Illuminate\Database\QueryException");

        Password::create([
            "event_id" => $event->id,
            "role_id" => $role->id,
            "password" => "duplicate-password",
        ]);
    }
}
