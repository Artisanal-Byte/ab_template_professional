<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\mock;

uses(RefreshDatabase::class);

it('auto selects the only tenant when visiting tenant routes without a session', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    mock(TenantProvisioner::class)
        ->shouldReceive('ensureProvisioned')
        ->andReturnNull();

    $this->actingAs($user);

    $response = $this->get('/tenant');

    $response->assertStatus(200);
    expect(session('tenant_id'))->toBe($tenant->id);
});

it('auto selects the first tenant when multiple tenants exist', function () {
    $user = User::factory()->create();
    $tenantA = Tenant::factory()->create();
    $tenantB = Tenant::factory()->create();

    TenantUser::factory()->create([
        'tenant_id' => $tenantA->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    TenantUser::factory()->create([
        'tenant_id' => $tenantB->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    mock(TenantProvisioner::class)
        ->shouldReceive('ensureProvisioned')
        ->andReturnNull();

    $this->actingAs($user);

    $response = $this->get('/tenant');

    $response->assertStatus(200);
    expect(session('tenant_id'))->toBe($tenantA->id);
});

it('redirects home to the tenant dashboard when only one tenant exists', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create();

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    mock(TenantProvisioner::class)
        ->shouldReceive('ensureProvisioned')
        ->andReturnNull();

    $this->actingAs($user);

    $response = $this->get('/');

    $response->assertRedirect('/tenant');
    expect(session('tenant_id'))->toBe($tenant->id);
});
