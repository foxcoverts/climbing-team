<?php

namespace Database\Factories;

use App\Enums\CommentNotificationOption;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationSettings>
 */
class NotificationSettingsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment_mail' => fake()->optional()->randomElement(CommentNotificationOption::class)?->value,
            'invite_mail' => fake()->optional()->boolean(),
            'change_mail' => fake()->optional()->boolean(),
            'confirm_mail' => fake()->optional()->boolean(),
            'cancel_mail' => fake()->optional()->boolean(),
        ];
    }
}
