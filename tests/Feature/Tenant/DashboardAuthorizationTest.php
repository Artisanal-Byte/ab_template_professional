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

it('allows owners to view the tenant dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($user);

    $this->withSession(['tenant_id' => $tenant->id])
        ->get(route('tenant.dashboard'))
        ->assertOk();
});

it('forbids non-owners from viewing the tenant dashboard', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'employee',
        'status' => 'active',
    ]);

    $this->actingAs($user);

    $this->withSession(['tenant_id' => $tenant->id])
        ->get(route('tenant.dashboard'))
        ->assertForbidden();
});
