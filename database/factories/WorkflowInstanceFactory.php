<?php

namespace Database\Factories;

use App\Models\DocumentVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowInstance>
 */
class WorkflowInstanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'document_version_id' => DocumentVersion::factory(),
            'status' => 'in_progress',
            'started_by' => (string) Str::uuid(),
        ];
    }
}
