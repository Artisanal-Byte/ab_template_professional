<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'organization_owner', 'guard_name' => 'web', 'tenant_id' => null]);
});

it('allows owners to assign permissions to a tenant role', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $role = Role::create([
        'name' => 'Auditor',
        'guard_name' => 'web',
        'tenant_id' => $tenant->id,
    ]);

    $permission = Permission::create([
        'name' => 'view_audits',
        'guard_name' => 'web',
        'tenant_id' => $tenant->id,
    ]);

    $this->actingAs($user);

    $response = $this->withSession(['tenant_id' => $tenant->id])
        ->patch(route('tenant.roles.permissions', $role), [
            'permissions' => [$permission->id],
        ]);

    $response->assertRedirect(route('tenant.roles.index'));

    expect($role->permissions()->where('permissions.id', $permission->id)->exists())->toBeTrue();
});
