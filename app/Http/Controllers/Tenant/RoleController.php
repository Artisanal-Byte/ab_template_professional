<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StoreRoleRequest;
use App\Http\Requests\Tenant\UpdateRolePermissionsRequest;
use App\Support\CurrentTenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();

        $roles = Role::query()
            ->where('tenant_id', $tenant?->id)
            ->with('permissions')
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role) => [
                'id' => $role->id,
                'name' => $role->name,
                'permission_count' => $role->permissions->count(),
                'permission_ids' => $role->permissions->pluck('id')->values()->all(),
            ]);

        $permissions = Permission::query()
            ->where('tenant_id', $tenant?->id)
            ->orderBy('name')
            ->get()
            ->map(fn (Permission $permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
            ]);

        return Inertia::render('tenant/Roles', [
            'roles' => $roles,
            'permissions' => $permissions,
        ]);
    }

    public function store(StoreRoleRequest $request): RedirectResponse
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();

        Role::create([
            'name' => $request->string('name')->toString(),
            'guard_name' => 'web',
            'tenant_id' => $tenant?->id,
        ]);

        return redirect()->route('tenant.roles.index');
    }

    public function destroy(Role $role): RedirectResponse
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();

        if ($role->tenant_id !== $tenant?->id) {
            abort(404);
        }

        $role->delete();

        return redirect()->route('tenant.roles.index');
    }

    public function updatePermissions(UpdateRolePermissionsRequest $request, Role $role): RedirectResponse
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();

        if ($role->tenant_id !== $tenant?->id) {
            abort(404);
        }

        $permissionIds = collect($request->input('permissions', []))
            ->filter()
            ->map(fn ($value) => (int) $value)
            ->values();

        $permissions = Permission::query()
            ->where('tenant_id', $tenant?->id)
            ->whereIn('id', $permissionIds)
            ->get();

        $role->syncPermissions($permissions);

        return redirect()->route('tenant.roles.index');
    }
}
