<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->setPermissionsTeamId(null);

        $webAdmin = User::firstOrCreate(
            ['email' => 'webadmin@neujin.test'],
            [
                'name' => 'Web Admin',
                'password' => Hash::make('Password!23'),
                'status' => 'active',
            ]
        );

        Role::firstOrCreate(['name' => 'web_admin', 'guard_name' => 'web', 'tenant_id' => null]);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web', 'tenant_id' => null]);
        Role::firstOrCreate(['name' => 'organization_owner', 'guard_name' => 'web', 'tenant_id' => null]);

        if (! $webAdmin->hasRole('web_admin')) {
            $webAdmin->assignRole('web_admin');
        }
    }
}
