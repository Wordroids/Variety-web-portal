<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RoleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private RoleService $service) {}

    public function index(): View
    {
        $this->authorize("viewAny", Role::class);

        return view('pages.roles.index', [
            'roles' => $this->service->getAllRoles(),
        ]);
    }

    public function create(): View
    {
        $this->authorize("create", Role::class);

        return view('pages.roles.create', [
            'permissions' => $this->service->getAllPermissions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize("create", Role::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:roles,name'],
            'permissions' => ['nullable', 'array'],
        ]);

        $role = $this->service->create($validated);

        return $role
            ? redirect()->route('roles.index')->with('success', 'Role created successfully.')
            : back()->with('error', 'Failed to create role.');
    }

    public function edit(Role $role): View
    {
        $this->authorize("update", $role);

        return view('pages.roles.edit', [
            'role' => $role->load('permissions'),
            'permissions' => $this->service->getAllPermissions(),
        ]);
    }

    public function update(Request $request, Role $role): RedirectResponse
    {
        $this->authorize("update", $role);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', "unique:roles,name,{$role->id}"],
            'permissions' => ['nullable', 'array'],
        ]);

        $ok = $this->service->update($role, $validated);

        return $ok
            ? redirect()->route('roles.index')->with('success', 'Role updated successfully.')
            : back()->with('error', 'Failed to update role.');
    }

    public function destroy(Role $role): RedirectResponse
    {
        $this->authorize("delete", $role);

        $ok = $this->service->delete($role);

        return $ok
            ? back()->with('success', 'Role deleted successfully.')
            : back()->with('error', 'Failed to delete role.');
    }
}
