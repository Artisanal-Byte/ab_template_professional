<?php

namespace Database\Factories;

use App\Models\WorkflowInstance;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkflowStep>
 */
class WorkflowStepFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'workflow_instance_id' => WorkflowInstance::factory(),
            'step_name' => $this->faker->words(2, true),
            'assigned_to' => (string) Str::uuid(),
            'status' => 'pending',
        ];
    }
}
