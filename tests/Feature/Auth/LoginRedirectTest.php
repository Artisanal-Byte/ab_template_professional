<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

use function Pest\Laravel\mock;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'web_admin', 'guard_name' => 'web', 'tenant_id' => null]);
    Role::create(['name' => 'admin', 'guard_name' => 'web', 'tenant_id' => null]);
    Role::create(['name' => 'organization_owner', 'guard_name' => 'web', 'tenant_id' => null]);

    mock(TenantProvisioner::class)
        ->shouldReceive('ensureProvisioned')
        ->andReturnNull();
});

it('redirects web admins to the platform dashboard', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $user->assignRole('web_admin');

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/platform');
});

it('redirects admins to the admin dashboard', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $user->assignRole('admin');

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/admin');
});

it('redirects tenant users with one tenant to the tenant dashboard', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $tenant = Tenant::factory()->create();

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/tenant');
    expect(session('tenant_id'))->toBe($tenant->id);
});

it('redirects tenant users to the tenant dashboard', function () {
    $user = User::factory()->withoutTwoFactor()->create();
    $tenantA = Tenant::factory()->create();
    $tenantB = Tenant::factory()->create();

    TenantUser::factory()->create([
        'tenant_id' => $tenantA->id,
        'user_id' => $user->id,
        'membership_role' => 'employee',
    ]);
    TenantUser::factory()->create([
        'tenant_id' => $tenantB->id,
        'user_id' => $user->id,
        'membership_role' => 'employee',
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect('/tenant');
    expect(session('tenant_id'))->toBe($tenantA->id);
});
