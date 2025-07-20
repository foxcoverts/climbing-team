<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->get('/profile')
            ->assertOk()
            ->assertSee('Profile Information');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'timezone' => 'UTC',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->name);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->patch('/profile', [
                'name' => 'Test User',
                'email' => $user->email,
                'timezone' => 'UTC',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($user->refresh()->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        // Note: Team Leader cannot delete their own account.
        $user = User::factory()->notTeamLeader()->create();

        $this
            ->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->delete('/profile', [
                'password' => 'password',
            ])
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        // Note: Team Leader cannot delete their own account.
        $user = User::factory()->notTeamLeader()->create();

        $this
            ->actingAs($user)
            ->withSession(['auth.password_confirmed_at' => time()])
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ])
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }
}
