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

it('forbids owners from editing their own membership', function () {
    $user = User::factory()->create();
    $user->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    $membership = TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($user);

    $response = $this->withSession(['tenant_id' => $tenant->id])
        ->patch(route('tenant.staff.update', $membership), [
            'membership_role' => 'employee',
            'status' => 'active',
        ]);

    $response->assertForbidden();
});

it('forbids owners from removing their own membership', function () {
    $user = User::factory()->create();
    $user->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    $membership = TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($user);

    $response = $this->withSession(['tenant_id' => $tenant->id])
        ->delete(route('tenant.staff.destroy', $membership));

    $response->assertForbidden();
});

it('blocks adding staff already linked to another organization', function () {
    $owner = User::factory()->create();
    $owner->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $owner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $existingUser = User::factory()->create([
        'email' => 'staff@acme.test',
    ]);

    $otherTenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $otherTenant->id,
        'user_id' => $existingUser->id,
        'membership_role' => 'employee',
        'status' => 'active',
    ]);

    $this->actingAs($owner);

    $response = $this->withSession(['tenant_id' => $tenant->id])
        ->post(route('tenant.staff.store'), [
            'name' => 'Staff Member',
            'email' => 'staff@acme.test',
            'membership_role' => 'employee',
        ]);

    $response->assertSessionHasErrors(['email']);
});
