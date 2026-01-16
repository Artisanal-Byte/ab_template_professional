<?php

namespace Database\Factories;

use App\Models\DocumentVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingAssignment>
 */
class TrainingAssignmentFactory extends Factory
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
            'core_user_id' => (string) Str::uuid(),
            'status' => 'assigned',
            'due_at' => now()->addWeek(),
        ];
    }
}
