<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
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

it('seeds default roles and permissions for new tenants', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    mock(\App\Services\Tenancy\TenantProvisioner::class)
        ->shouldReceive('ensureProvisioned')
        ->andReturnNull();

    $this->actingAs($admin)->post(route('admin.tenants.store'), [
        'name' => 'Neujin Bio Labs',
        'slug' => 'neujin_labs',
        'owner_name' => 'Org Owner',
        'owner_email' => 'owner@neujin.test',
        'owner_password' => 'password',
    ])->assertRedirect(route('admin.tenants.index'));

    $tenant = Tenant::query()->where('slug', 'neujin_labs')->firstOrFail();
    $owner = User::query()->where('email', 'owner@neujin.test')->firstOrFail();
    $registrar = app(PermissionRegistrar::class);
    $previousTeam = $registrar->getPermissionsTeamId();

    $registrar->setPermissionsTeamId($tenant->id);

    $roleNames = Role::query()->where('tenant_id', $tenant->id)->pluck('name')->all();
    $permissionNames = Permission::query()->where('tenant_id', $tenant->id)->pluck('name')->all();

    expect($roleNames)->toContain('Administrator');
    expect($roleNames)->toContain('Creator');
    expect($roleNames)->toContain('Reviewer');
    expect($roleNames)->toContain('User');
    expect($permissionNames)->toContain('documents.view');
    expect($permissionNames)->toContain('documents.create');
    expect($permissionNames)->toContain('documents.edit');
    expect($permissionNames)->toContain('documents.review');

    expect($owner->roles()->where('roles.tenant_id', $tenant->id)->where('name', 'Administrator')->exists())
        ->toBeTrue();

    $registrar->setPermissionsTeamId($previousTeam);
});
