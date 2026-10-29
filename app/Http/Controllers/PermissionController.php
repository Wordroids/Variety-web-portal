<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $this->authorize("viewAny", Permission::class);

        $permissions = Permission::orderBy('name')->paginate(20);
        return view('pages.permissions.index', compact('permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize("create", Permission::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name'],
        ]);

        Permission::create(['name' => $validated['name'], 'guard_name' => 'web']);

        return back()->with('success', 'Permission added successfully.');
    }

    public function update(Request $request, Permission $permission): RedirectResponse
    {
        $this->authorize("update", $permission);

        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:permissions,name,' . $permission->id],
        ]);

        $permission->update(['name' => $validated['name']]);

        return back()->with('success', 'Permission updated successfully.');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        $this->authorize("delete", $permission);

        $permission->delete();
        return back()->with('success', 'Permission deleted.');
    }
}
