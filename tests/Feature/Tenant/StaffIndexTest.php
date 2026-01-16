<?php

use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
    app(PermissionRegistrar::class)->setPermissionsTeamId(null);

    Role::create(['name' => 'organization_owner', 'guard_name' => 'web', 'tenant_id' => null]);
});

it('exposes role assignment availability per staff member', function () {
    $owner = User::factory()->create(['email_verified_at' => now()]);
    $owner->assignRole('organization_owner');

    $tenant = Tenant::factory()->create(['provisioned_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $owner->id,
        'membership_role' => 'owner',
        'status' => 'active',
    ]);

    $employee = User::factory()->create(['email_verified_at' => now()]);

    TenantUser::factory()->create([
        'tenant_id' => $tenant->id,
        'user_id' => $employee->id,
        'membership_role' => 'employee',
        'status' => 'active',
    ]);

    $this->actingAs($owner)
        ->withSession(['tenant_id' => $tenant->id])
        ->get(route('tenant.staff.index'))
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('tenant/Staff')
            ->has('staff', 2)
            ->where('staff.0.membership_role', 'owner')
            ->where('staff.0.can_assign_role', false)
            ->where('staff.1.membership_role', 'employee')
            ->where('staff.1.can_assign_role', true)
        );
});
