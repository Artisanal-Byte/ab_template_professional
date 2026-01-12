<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\StorePermissionRequest;
use App\Support\CurrentTenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();

        $permissions = Permission::query()
            ->where('tenant_id', $tenant?->id)
            ->orderBy('name')
            ->get()
            ->map(fn (Permission $permission) => [
                'id' => $permission->id,
                'name' => $permission->name,
            ]);

        return Inertia::render('tenant/Permissions', [
            'permissions' => $permissions,
        ]);
    }

    public function store(StorePermissionRequest $request): RedirectResponse
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();

        Permission::create([
            'name' => $request->string('name')->toString(),
            'guard_name' => 'web',
            'tenant_id' => $tenant?->id,
        ]);

        return redirect()->route('tenant.permissions.index');
    }

    public function destroy(Permission $permission): RedirectResponse
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();

        if ($permission->tenant_id !== $tenant?->id) {
            abort(404);
        }

        $permission->delete();

        return redirect()->route('tenant.permissions.index');
    }
}
