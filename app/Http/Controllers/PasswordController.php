<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Password;
use App\Models\Role;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Event $event)
    {
        $roles = Role::paginate(10);
        return view("pages.passwords.index", compact("roles", "event"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event, $role_id)
    {
        $validated = $request->validate([
            "password" => ["required", "string"],
        ]);

        Password::updateOrCreate(
            ["role_id" => $role_id, "event_id" => $event->id],
            ["password" => $validated["password"]],
        );

        return back()->with("success", "Password updated successfully.");
    }
}
