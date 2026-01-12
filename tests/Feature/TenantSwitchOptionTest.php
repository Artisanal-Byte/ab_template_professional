<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('does not expose the tenant select route', function () {
    $user = User::factory()->create();
    $tenantA = Tenant::factory()->create(['provisioned_at' => now()]);
    $tenantB = Tenant::factory()->create(['provisioned_at' => now()]);

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

    $this->actingAs($user);

    $this->get('/tenant/select')->assertNotFound();
});
