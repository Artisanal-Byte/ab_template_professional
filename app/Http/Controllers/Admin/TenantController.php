<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTenantRequest;
use App\Http\Requests\Admin\UpdateTenantRequest;
use App\Http\Requests\Admin\UpdateTenantStatusRequest;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class TenantController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('manage-tenants');

        $tenants = Tenant::query()
            ->with(['memberships' => function ($query) {
                $query->where('membership_role', 'owner')->with('user');
            }])
            ->orderBy('name')
            ->get()
            ->map(fn (Tenant $tenant) => [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'status' => $tenant->status,
                'provisioned_at' => $tenant->provisioned_at?->toDateTimeString(),
                'owner' => $tenant->memberships->first()?->user ? [
                    'id' => $tenant->memberships->first()->user->id,
                    'name' => $tenant->memberships->first()->user->name,
                    'email' => $tenant->memberships->first()->user->email,
                ] : null,
            ]);

        return Inertia::render('admin/Tenants', [
            'tenants' => $tenants,
        ]);
    }

    public function store(StoreTenantRequest $request, TenantProvisioner $provisioner): RedirectResponse
    {
        Gate::authorize('manage-tenants');

        $ownerEmail = $request->string('owner_email')->toString();
        $owner = User::query()->where('email', $ownerEmail)->first();

        if ($owner && $owner->tenantMemberships()->exists()) {
            throw ValidationException::withMessages([
                'owner_email' => 'This user is already assigned to another organization.',
            ]);
        }

        if (! $owner && ! $request->filled('owner_password')) {
            throw ValidationException::withMessages([
                'owner_password' => 'Password is required when creating a new owner.',
            ]);
        }

        $slug = $request->string('slug')->toString();
        $slug = $slug !== '' ? $slug : Str::slug($request->string('name')->toString(), '_');

        $slug = Str::lower($slug);

        if (Tenant::query()->where('slug', $slug)->exists()) {
            $slug = $slug.'_'.Str::lower(Str::random(4));
        }

        $dbName = 'tenant_'.$slug;

        if (Tenant::query()->where('db_name', $dbName)->exists()) {
            $dbName = $dbName.'_'.Str::lower(Str::random(4));
        }

        $tenant = DB::transaction(function () use ($request, $owner, $ownerEmail, $slug, $dbName) {
            $tenant = Tenant::create([
                'name' => $request->string('name')->toString(),
                'slug' => $slug,
                'db_name' => $dbName,
                'status' => 'active',
            ]);

            $ownerUser = $owner ?: User::create([
                'uuid' => (string) Str::uuid(),
                'name' => $request->string('owner_name')->toString(),
                'email' => $ownerEmail,
                'password' => Hash::make($request->string('owner_password')->toString()),
                'status' => 'active',
            ]);

            if (! $ownerUser->hasRole('organization_owner')) {
                $ownerUser->assignRole('organization_owner');
            }

            if ($ownerUser->tenantMemberships()->exists()) {
                throw ValidationException::withMessages([
                    'owner_email' => 'This user is already assigned to another organization.',
                ]);
            }

            TenantUser::firstOrCreate([
                'tenant_id' => $tenant->id,
                'user_id' => $ownerUser->id,
            ], [
                'membership_role' => 'owner',
                'status' => 'active',
            ]);

            $this->assignTenantOwnerRole($tenant, $ownerUser);

            return $tenant;
        });

        $provisioner->ensureProvisioned($tenant);

        return redirect()->route('admin.tenants.index');
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant): RedirectResponse
    {
        Gate::authorize('manage-tenants');

        $slug = $request->string('slug')->toString();
        $slug = $slug !== '' ? Str::lower($slug) : $tenant->slug;

        DB::transaction(function () use ($request, $tenant, $slug): void {
            $tenant->update([
                'name' => $request->string('name')->toString(),
                'slug' => $slug,
            ]);

            $ownerEmail = $request->string('owner_email')->toString();
            $ownerPassword = $request->string('owner_password')->toString();
            $ownerMembership = $tenant->memberships()
                ->where('membership_role', 'owner')
                ->with('user')
                ->first();
            $currentOwner = $ownerMembership?->user;
            $ownerUser = $currentOwner;

            if ($currentOwner) {
                if ($currentOwner->email !== $ownerEmail) {
                    $existingOwner = User::query()->where('email', $ownerEmail)->first();

                    if ($existingOwner && $existingOwner->id !== $currentOwner->id) {
                        if ($existingOwner->tenantMemberships()->where('tenant_id', '!=', $tenant->id)->exists()) {
                            throw ValidationException::withMessages([
                                'owner_email' => 'This user is already assigned to another organization.',
                            ]);
                        }

                        $ownerUser = $existingOwner;
                    } elseif ($currentOwner->tenantMemberships()->where('tenant_id', '!=', $tenant->id)->exists()) {
                        if (! $request->filled('owner_password')) {
                            throw ValidationException::withMessages([
                                'owner_password' => 'Password is required when assigning a new owner.',
                            ]);
                        }

                        $ownerUser = User::create([
                            'uuid' => (string) Str::uuid(),
                            'name' => $tenant->name.' Owner',
                            'email' => $ownerEmail,
                            'password' => Hash::make($ownerPassword),
                            'status' => 'active',
                        ]);
                    } else {
                        $currentOwner->update([
                            'email' => $ownerEmail,
                        ]);
                    }
                }
            }

            if (! $ownerUser) {
                $ownerUser = User::query()->where('email', $ownerEmail)->first();

                if (! $ownerUser && ! $request->filled('owner_password')) {
                    throw ValidationException::withMessages([
                        'owner_password' => 'Password is required when assigning a new owner.',
                    ]);
                }

                $ownerUser = $ownerUser ?: User::create([
                    'uuid' => (string) Str::uuid(),
                    'name' => $tenant->name.' Owner',
                    'email' => $ownerEmail,
                    'password' => Hash::make($ownerPassword),
                    'status' => 'active',
                ]);
            }

            if ($ownerUser->tenantMemberships()->where('tenant_id', '!=', $tenant->id)->exists()) {
                throw ValidationException::withMessages([
                    'owner_email' => 'This user is already assigned to another organization.',
                ]);
            }

            if (! $ownerUser->hasRole('organization_owner')) {
                $ownerUser->assignRole('organization_owner');
            }

            $this->assignTenantOwnerRole($tenant, $ownerUser);

            $existingMembership = TenantUser::query()
                ->where('tenant_id', $tenant->id)
                ->where('user_id', $ownerUser->id)
                ->first();

            if ($existingMembership) {
                $existingMembership->update([
                    'membership_role' => 'owner',
                    'status' => 'active',
                ]);
                if ($ownerMembership && $ownerMembership->id !== $existingMembership->id) {
                    $ownerMembership->update([
                        'membership_role' => 'employee',
                        'status' => 'active',
                    ]);
                }
            } elseif ($ownerMembership) {
                $ownerMembership->update([
                    'user_id' => $ownerUser->id,
                    'membership_role' => 'owner',
                    'status' => 'active',
                ]);
            } else {
                TenantUser::create([
                    'tenant_id' => $tenant->id,
                    'user_id' => $ownerUser->id,
                    'membership_role' => 'owner',
                    'status' => 'active',
                ]);
            }

            if ($request->filled('owner_password')) {
                $ownerUser->update([
                    'password' => Hash::make($request->string('owner_password')->toString()),
                ]);
            }
        });

        return redirect()->route('admin.tenants.index');
    }

    private function assignTenantOwnerRole(Tenant $tenant, User $ownerUser): void
    {
        $registrar = app(PermissionRegistrar::class);
        $previousTeam = $registrar->getPermissionsTeamId();

        $registrar->setPermissionsTeamId($tenant->id);

        $role = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
            'tenant_id' => $tenant->id,
        ]);

        $ownerUser->roles()->syncWithoutDetaching([
            $role->id => ['tenant_id' => $tenant->id],
        ]);

        $registrar->setPermissionsTeamId($previousTeam);
    }

    public function updateStatus(UpdateTenantStatusRequest $request, Tenant $tenant): RedirectResponse
    {
        Gate::authorize('manage-tenants');

        $tenant->update([
            'status' => $request->string('status'),
        ]);

        return redirect()->route('admin.tenants.index');
    }
}
