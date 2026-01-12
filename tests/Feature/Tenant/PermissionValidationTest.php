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

it('returns a validation error when a permission already exists', function () {
    $user = User::factory()->create(['email_verified_at' => now()]);
    $user->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    Permission::create([
        'name' => 'documents.read',
        'guard_name' => 'web',
        'tenant_id' => $tenant->id,
    ]);

    $this->actingAs($user);

    $response = $this->withSession(['tenant_id' => $tenant->id])
        ->post(route('tenant.permissions.store'), [
            'name' => 'documents.read',
        ]);

    $response->assertSessionHasErrors(['name']);
});
