<?php

namespace App\Http\Responses;

use App\Services\Tenancy\TenantProvisioner;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\VerifyEmailResponse as VerifyEmailResponseContract;

class VerifyEmailResponse implements VerifyEmailResponseContract
{
    public function __construct(private TenantProvisioner $provisioner) {}

    public function toResponse($request)
    {
        /** @var Request $request */
        $user = $request->user();

        if ($user->hasRole('web_admin')) {
            return redirect()->intended(route('platform.dashboard', absolute: false).'?verified=1');
        }

        if ($user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false).'?verified=1');
        }

        $memberships = $user->tenantMemberships()
            ->where('status', 'active')
            ->with('tenant')
            ->get();

        $tenant = $memberships->first()?->tenant;

        if ($tenant) {
            $this->provisioner->ensureProvisioned($tenant);
            $request->session()->put('tenant_id', $tenant->id);

            return redirect()->intended(route('tenant.dashboard', absolute: false).'?verified=1');
        }

        abort(403);
    }
}
