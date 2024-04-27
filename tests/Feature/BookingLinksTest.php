<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingLinksTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_links(): void
    {
        $user = User::factory()->create();

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
    }
}
