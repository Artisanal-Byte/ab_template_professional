<?php

namespace App\Http\Middleware;

use App\Support\CurrentTenant;
use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        [$message, $author] = str(Inspiring::quotes()->random())->explode('-');
        $currentTenant = app(CurrentTenant::class)->get();
        $memberships = $request->user()
            ? $request->user()->tenantMemberships()->with('tenant')->get()
            : collect();
        $sessionTenantId = $request->session()->get('tenant_id');

        if (! $currentTenant && $sessionTenantId) {
            $currentTenant = $memberships
                ->firstWhere('tenant_id', $sessionTenantId)
                ?->tenant;
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'quote' => ['message' => trim($message), 'author' => trim($author)],
            'auth' => [
                'user' => $request->user(),
                'roles' => $request->user()?->getRoleNames(),
            ],
            'currentTenant' => $currentTenant ? [
                'id' => $currentTenant->id,
                'name' => $currentTenant->name,
                'slug' => $currentTenant->slug,
            ] : null,
            'tenantMemberships' => $memberships->map(function ($membership) {
                return [
                    'tenant_id' => $membership->tenant_id,
                    'tenant_name' => $membership->tenant?->name,
                    'membership_role' => $membership->membership_role,
                    'status' => $membership->status,
                ];
            }),
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
