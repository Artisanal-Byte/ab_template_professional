<?php

use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\DesignSystemController;
use App\Http\Controllers\Platform\AdminUserController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\PermissionController;
use App\Http\Controllers\Tenant\RoleController;
use App\Http\Controllers\Tenant\StaffController;
use App\Models\TenantUser;
use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (! auth()->check()) {
        return redirect()->route('login');
    }

    $user = auth()->user();

    if ($user->hasRole('web_admin')) {
        return redirect()->route('platform.dashboard');
    }

    if ($user->hasRole('admin')) {
        return redirect()->route('admin.dashboard');
    }

    $memberships = $user->tenantMemberships()
        ->where('status', 'active')
        ->with('tenant')
        ->get()
        ->filter(fn (TenantUser $membership) => $membership->tenant?->status === 'active')
        ->values();

    $tenant = $memberships->first()?->tenant;

    if ($tenant) {
        app(TenantProvisioner::class)->ensureProvisioned($tenant);
        request()->session()->put('tenant_id', $tenant->id);

        return redirect()->route('tenant.dashboard');
    }

    abort(403);
})->name('home');

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::prefix('platform')
        ->name('platform.')
        ->middleware('role:web_admin')
        ->group(function (): void {
            Route::get('/', [AdminUserController::class, 'index'])->name('dashboard');
            Route::get('admin-users', [AdminUserController::class, 'index'])->name('admin-users.index');
            Route::post('admin-users', [AdminUserController::class, 'store'])->name('admin-users.store');
            Route::patch('admin-users/{user}', [AdminUserController::class, 'update'])->name('admin-users.update');
            Route::delete('admin-users/{user}', [AdminUserController::class, 'destroy'])->name('admin-users.destroy');
        });

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function (): void {
            Route::get('/', [TenantController::class, 'index'])->name('dashboard');
            Route::get('tenants', [TenantController::class, 'index'])->name('tenants.index');
            Route::post('tenants', [TenantController::class, 'store'])->name('tenants.store');
            Route::patch('tenants/{tenant}', [TenantController::class, 'update'])->name('tenants.update');
            Route::patch('tenants/{tenant}/status', [TenantController::class, 'updateStatus'])->name('tenants.status');
        });

    Route::prefix('tenant')
        ->name('tenant.')
        ->middleware(['tenant.selected', 'tenant.membership', 'tenant.context'])
        ->group(function (): void {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
            Route::get('staff', [StaffController::class, 'index'])->name('staff.index');
            Route::post('staff', [StaffController::class, 'store'])->name('staff.store');
            Route::patch('staff/{tenantUser}', [StaffController::class, 'update'])->name('staff.update');
            Route::delete('staff/{tenantUser}', [StaffController::class, 'destroy'])->name('staff.destroy');
            Route::post('staff/assign-role', [StaffController::class, 'assignRole'])->name('staff.assign-role');

            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
            Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
            Route::patch('roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions');
            Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

            Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
            Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store');
            Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');
        });
});

if (! app()->isProduction()) {
    Route::get('/design_system_and_components', [DesignSystemController::class, 'show'])
        ->middleware(['auth', 'verified'])
        ->name('design.system');
}

require __DIR__.'/settings.php';
