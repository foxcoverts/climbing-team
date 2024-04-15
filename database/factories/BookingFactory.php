<?php

namespace Database\Factories;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = new Carbon(
            fake()->dateTimeBetween('now', '+1 year'),
            'Europe/London'
        );
        $activity = fake()->randomElement([
            'Abseiling',
            'Climbing',
            'Climbing & Abseiling',
        ]);
        $status = fake()->randomElement([
            BookingStatus::Confirmed->value,
            BookingStatus::Tentative->value,
        ]);

        return [
            'start_at' => $start,
            'end_at' => $start->addHours(2),
            'location' => 'Fox Coverts Campsite',
            'activity' => $activity,
            'group_name' => fake()->scoutGroupName(),
            'notes' => 'x12 Young People',
            'status' => $status,
        ];
    }

    /**
     * Get a confirmed booking.
     */
    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Confirmed->value,
        ]);
    }

    /**
     * Get a tentative booking.
     */
    public function tentative(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => BookingStatus::Tentative->value,
        ]);
    }
}
