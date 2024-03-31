<?php

namespace Database\Factories;

use App\Enums\MountainTrainingAward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MountainTrainingQualification>
 */
class MountainTrainingQualificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'award' => MountainTrainingAward::ClimbingWallInstructor->value,
        ];
    }
}
