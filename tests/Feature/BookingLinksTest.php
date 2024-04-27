<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_links_with_no_ical_token_generates_a_new_token(): void
    {
        $user = User::factory()->create([
            'ical_token' => null,
        ]);

        $this
            ->actingAs($user)
            ->get('/booking/links')
            ->assertOk()
            ->assertViewIs('booking.links')
            ->assertSeeInOrder([
                'Calendar Links',
                'Rota Link',
                'Calendar Link',
            ]);

        // A new ical token has been generated
        $user->refresh();
        $this->assertNotNull($user->ical_token);
    }

    public function test_booking_links_uses_existing_ical_token(): void
    {
        $ical_token = User::generateToken();
        $user = User::factory()->create([
            'ical_token' => $ical_token,
        ]);

        $this
            ->actingAs($user)
            ->get('/booking/links')
            ->assertOk()
            ->assertViewIs('booking.links')
            ->assertSeeInOrder([
                'Calendar Links',
                'Rota Link',
                'Calendar Link',
            ]);

        // Existing ical token remains
        $user->refresh();
        $this->assertEquals($ical_token, $user->ical_token);
    }

    public function test_reset_booking_links_generates_new_ical_token(): void
    {
        $ical_token = User::generateToken();
        $user = User::factory()->create([
            'ical_token' => $ical_token,
        ]);

        $this
            ->actingAs($user)
            ->delete('/booking/links')
            ->assertRedirectToRoute('booking.links');

        // A new ical token has been generated.
        $user->refresh();
        $this->assertNotEquals($ical_token, $user->ical_token);
    }
}
