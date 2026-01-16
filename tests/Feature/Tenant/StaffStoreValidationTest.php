<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'organization_owner', 'guard_name' => 'web', 'tenant_id' => null]);
});

it('requires a password when adding staff', function () {
    $owner = User::factory()->create(['email_verified_at' => now()]);
    $owner->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $owner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($owner)
        ->withSession(['tenant_id' => $tenant->id])
        ->post(route('tenant.staff.store'), [
            'name' => 'Staff Member',
            'email' => 'staff@acme.test',
            'membership_role' => 'employee',
        ])
        ->assertSessionHasErrors(['password']);
});

it('requires a role when adding staff', function () {
    $owner = User::factory()->create(['email_verified_at' => now()]);
    $owner->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $owner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($owner)
        ->withSession(['tenant_id' => $tenant->id])
        ->post(route('tenant.staff.store'), [
            'name' => 'Staff Member',
            'email' => 'staff@acme.test',
            'password' => 'Password123!',
            'membership_role' => 'employee',
        ])
        ->assertSessionHasErrors(['role_id']);
});

it('assigns the selected role when adding staff', function () {
    $owner = User::factory()->create(['email_verified_at' => now()]);
    $owner->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $owner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $role = Role::create([
        'name' => 'Auditor',
        'guard_name' => 'web',
        'tenant_id' => $tenant->id,
    ]);

    $this->actingAs($owner)
        ->withSession(['tenant_id' => $tenant->id])
        ->post(route('tenant.staff.store'), [
            'name' => 'Staff Member',
            'email' => 'staff@acme.test',
            'password' => 'Password123!',
            'membership_role' => 'employee',
            'role_id' => $role->id,
        ])
        ->assertRedirect(route('tenant.staff.index'));

    $staff = User::query()->where('email', 'staff@acme.test')->firstOrFail();

    expect($staff->roles()->where('roles.id', $role->id)->exists())->toBeTrue();
});
