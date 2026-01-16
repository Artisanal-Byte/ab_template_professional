<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        $slug = Str::slug($name, '_').'_'.$this->faker->unique()->numberBetween(100, 999);
        $dbName = 'tenant_'.$slug;

        return [
            'name' => $name,
            'slug' => $slug,
            'db_name' => $dbName,
            'db_username' => $dbName,
            'db_password' => Crypt::encryptString(Str::random(24)),
            'db_host' => '127.0.0.1',
            'db_port' => 5432,
            'status' => 'active',
        ];
    }
}
