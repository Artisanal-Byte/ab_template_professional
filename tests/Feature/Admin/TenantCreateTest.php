<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\mock;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'admin', 'guard_name' => 'web', 'tenant_id' => null]);
    Role::create(['name' => 'organization_owner', 'guard_name' => 'web', 'tenant_id' => null]);
});

it('creates tenants without db host or port inputs', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    mock(\App\Services\Tenancy\TenantProvisioner::class)
        ->shouldReceive('ensureProvisioned')
        ->andReturnNull();

    $this->actingAs($admin);

    $response = $this->post(route('admin.tenants.store'), [
        'name' => 'Neujin Bio Labs',
        'slug' => 'neujin_labs',
        'owner_name' => 'Org Owner',
        'owner_email' => 'owner@neujin.test',
        'owner_password' => 'password',
    ]);

    $response->assertRedirect(route('admin.tenants.index'));
});

it('blocks assigning owners already linked to another organization', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $existingOwner = User::factory()->create([
        'email' => 'owner@neujin.test',
    ]);

    $tenant = Tenant::factory()->create();
    $tenant->memberships()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $existingOwner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    mock(\App\Services\Tenancy\TenantProvisioner::class)
        ->shouldReceive('ensureProvisioned')
        ->andReturnNull();

    $this->actingAs($admin);

    $response = $this->post(route('admin.tenants.store'), [
        'name' => 'Neujin Bio Labs',
        'slug' => 'neujin_labs',
        'owner_name' => 'Org Owner',
        'owner_email' => 'owner@neujin.test',
        'owner_password' => 'password',
    ]);

    $response->assertSessionHasErrors(['owner_email']);
});
