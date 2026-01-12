<?php

namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Http\Requests\Platform\StoreAdminUserRequest;
use App\Http\Requests\Platform\UpdateAdminUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class AdminUserController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('manage-admins');

        $admins = User::query()
            ->role('admin')
            ->orderBy('name')
            ->get()
            ->map(fn (User $user) => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'status' => $user->status,
            ]);

        return Inertia::render('platform/AdminUsers', [
            'admins' => $admins,
        ]);
    }

    public function store(StoreAdminUserRequest $request): RedirectResponse
    {
        Gate::authorize('manage-admins');

        $user = User::create([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'password' => Hash::make($request->string('password')->toString()),
            'status' => 'active',
        ]);

        $user->assignRole('admin');

        return redirect()->route('platform.admin-users.index');
    }

    public function update(UpdateAdminUserRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('manage-admins');

        if ($user->hasRole('web_admin')) {
            abort(403);
        }

        $user->fill([
            'name' => $request->string('name')->toString(),
            'email' => $request->string('email')->toString(),
            'status' => $request->string('status')->toString(),
        ]);

        if ($request->filled('password')) {
            $user->password = Hash::make($request->string('password')->toString());
        }

        $user->save();

        if (! $user->hasRole('admin')) {
            $user->assignRole('admin');
        }

        return redirect()->route('platform.admin-users.index');
    }

    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('manage-admins');

        if ($user->hasRole('web_admin') || $user->id === request()->user()?->id) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('platform.admin-users.index');
    }
}
