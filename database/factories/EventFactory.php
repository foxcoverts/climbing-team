<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
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
        $group = fake()->randomElement([
            '1st Barwell',
            '1st Britannia',
            '1st Earl Shilton',
            '1st Hinckley',
            '1st Sapcote',
            '1st Stoke Golding',
            '1st Stoney Stanton',
            '2nd Hinckley',
            '6th Hinckley',
            '11th Hinckley',
            '12th Hinckley',
        ]);
        $section = fake()->randomElement([
            'Beavers',
            'Cubs',
            'Scouts',
            'Explorers',
        ]);

        return [
            'start_on' => $start->format('Y-m-d'),
            'start_at' => '09:00',
            'end_at' => '11:00',
            'location' => 'Fox Coverts Campsite',
            'group_name' => "$group $section",
            'notes' => "x12 $section",
        ];
    }
}
