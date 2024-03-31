<?php

namespace Database\Factories;

use App\Enums\ScoutPermitType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScoutPermit>
 */
class ScoutPermitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'activity' => 'Climbing and Abseiling',
            'category' => 'Artificial Top Rope',
            'type' => ScoutPermitType::Leadership->value,
            'restrictions' => 'Setup and run a single top-rope climb on towers in Leicestershire.',
        ];
    }
}
