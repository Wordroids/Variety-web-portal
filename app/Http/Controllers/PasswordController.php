<?php

namespace App\Http\Controllers;

use App\Models\Password;
use App\Models\Role;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::paginate(10);
        return view('pages.passwords.index', compact('roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $role_id)
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        Password::updateOrCreate(['role_id' => $role_id],[
            'password' => $validated['password']
        ]);

        return back()->with('success', 'Password updated successfully.');
    }
}
