<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get('/tenant');
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);
    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $user->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $this->actingAs($user);

    $response = $this->withSession(['tenant_id' => $tenant->id])
        ->get('/tenant');
    $response->assertStatus(200);
});
