<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\AssignRoleRequest;
use App\Http\Requests\Tenant\StoreStaffRequest;
use App\Http\Requests\Tenant\UpdateStaffRequest;
use App\Models\TenantUser;
use App\Models\User;
use App\Support\CurrentTenant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;

class StaffController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('manage-tenant-staff');

        $tenant = app(CurrentTenant::class)->get();

        $memberships = TenantUser::query()
            ->where('tenant_id', $tenant?->id)
            ->with('user')
            ->orderBy('id')
            ->get()
            ->map(fn (TenantUser $membership) => [
                'id' => $membership->id,
                'user_id' => $membership->user_id,
                'name' => $membership->user?->name,
                'email' => $membership->user?->email,
                'membership_role' => $membership->membership_role,
                'status' => $membership->status,
                'roles' => $membership->user?->getRoleNames()->values()->all() ?? [],
            ]);

        $roles = Role::query()
            ->where('tenant_id', $tenant?->id)
            ->orderBy('name')
            ->get()
            ->map(fn (Role $role) => [
                'id' => $role->id,
                'name' => $role->name,
            ]);

        return Inertia::render('tenant/Staff', [
            'staff' => $memberships,
            'roles' => $roles,
        ]);
    }

    public function store(StoreStaffRequest $request): RedirectResponse
    {
        Gate::authorize('manage-tenant-staff');

        $tenant = app(CurrentTenant::class)->get();

        $user = User::query()->where('email', $request->string('email')->toString())->first();

        if ($user && $user->tenantMemberships()->where('tenant_id', '!=', $tenant?->id)->exists()) {
            throw ValidationException::withMessages([
                'email' => 'This user is already assigned to another organization.',
            ]);
        }

        if (! $user && ! $request->filled('password')) {
            throw ValidationException::withMessages([
                'password' => 'Password is required when creating a new staff user.',
            ]);
        }

        $user = $user ?: User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'status' => 'active',
        ]);

        TenantUser::updateOrCreate([
            'tenant_id' => $tenant?->id,
            'user_id' => $user->id,
        ], [
            'membership_role' => $request->string('membership_role')->toString(),
            'status' => 'active',
        ]);

        return redirect()->route('tenant.staff.index');
    }

    public function update(UpdateStaffRequest $request, TenantUser $tenantUser): RedirectResponse
    {
        Gate::authorize('manage-tenant-staff');

        $tenant = app(CurrentTenant::class)->get();
        $user = $request->user();

        if ($tenantUser->tenant_id !== $tenant?->id) {
            abort(404);
        }

        if ($user && $tenantUser->user_id === $user->id) {
            abort(403);
        }

        $tenantUser->update([
            'membership_role' => $request->string('membership_role')->toString(),
            'status' => $request->string('status')->toString(),
        ]);

        return redirect()->route('tenant.staff.index');
    }

    public function destroy(TenantUser $tenantUser): RedirectResponse
    {
        Gate::authorize('manage-tenant-staff');

        $tenant = app(CurrentTenant::class)->get();
        $user = request()->user();

        if ($tenantUser->tenant_id !== $tenant?->id) {
            abort(404);
        }

        if ($user && $tenantUser->user_id === $user->id) {
            abort(403);
        }

        $tenantUser->delete();

        return redirect()->route('tenant.staff.index');
    }

    public function assignRole(AssignRoleRequest $request): RedirectResponse
    {
        Gate::authorize('manage-tenant-roles');

        $tenant = app(CurrentTenant::class)->get();
        $role = Role::query()
            ->where('id', $request->integer('role_id'))
            ->where('tenant_id', $tenant?->id)
            ->firstOrFail();

        $user = User::query()->findOrFail($request->integer('user_id'));

        $user->assignRole($role);

        return redirect()->route('tenant.staff.index');
    }
}
