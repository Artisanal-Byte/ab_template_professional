<?php

namespace App\Http\Middleware;

use App\Models\TenantUser;
use App\Services\Tenancy\TenantProvisioner;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantSelected
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-ID') ?: $request->session()->get('tenant_id');

        if (! $tenantId) {
            $user = $request->user();

            if ($user) {
                $memberships = $user->tenantMemberships()
                    ->where('status', 'active')
                    ->with('tenant')
                    ->get()
                    ->filter(fn (TenantUser $membership) => $membership->tenant?->status === 'active')
                    ->values();

                $tenant = $memberships->first()?->tenant;

                if ($tenant) {
                    app(TenantProvisioner::class)->ensureProvisioned($tenant);
                    $request->session()->put('tenant_id', $tenant->id);

                    return $next($request);
                }
            }

            if ($request->expectsJson()) {
                return response()->json(['message' => 'Tenant selection required.'], 409);
            }

            abort(403);
        }

        return $next($request);
    }
}
