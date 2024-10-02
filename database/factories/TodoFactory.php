<?php

namespace Database\Factories;

use App\Enums\TodoStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(TodoStatus::class);

        $completedAt = match ($status) {
            TodoStatus::Completed => fake()->dateTime(),
            default => null,
        };

        $startedAt = match ($status) {
            TodoStatus::InProcess,
            TodoStatus::Completed => fake()->dateTime($completedAt ?? 'now'),
            default => null,
        };

        return [
            'summary' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'location' => fake()->address(),
            'priority' => 5,
            'status' => $status,
            'due_at' => fake()->optional()->dateTime(),
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'sequence' => 0,
        ];
    }
}
