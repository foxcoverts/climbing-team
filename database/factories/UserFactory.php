<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Enums\Section;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        if (fake()->boolean()) {
            $emergency_name = fake()->name();
            $emergency_phone = fake()->e164PhoneNumber();
        } else {
            $emergency_name = null;
            $emergency_phone = null;
        }

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'timezone' => 'UTC',
            'role' => fake()->randomElement(Role::class)->value,
            'section' => fake()->randomElement(Section::class)->value,
            'phone' => fake()->optional()->e164PhoneNumber(),
            'emergency_name' => $emergency_name,
            'emergency_phone' => $emergency_phone,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Get a guest.
     */
    public function guest(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::Guest->value,
        ]);
    }

    /**
     * Get a team member.
     */
    public function teamMember(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::TeamMember->value,
        ]);
    }

    /**
     * Get a team leader.
     */
    public function teamLeader(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => Role::TeamLeader->value,
        ]);
    }
}
