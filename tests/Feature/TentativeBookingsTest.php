<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TentativeBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_leader_can_see_tentative_bookings(): void
    {
        $user = User::factory()->teamLeader()->create();
        $booking = Booking::factory()->tentative()->create();
        $bookingSummary = __(':activity for :group at :location', [
            'activity' => $booking->activity,
            'group' => $booking->group_name,
            'location' => $booking->location,
        ]);

        $otherBooking = Booking::factory()->confirmed()->create();
        $otherBookingSummary = __(':activity for :group at :location', [
            'activity' => $otherBooking->activity,
            'group' => $otherBooking->group_name,
            'location' => $otherBooking->location,
        ]);

        $this
            ->actingAs($user)
            ->get('/booking/tentative')
            ->assertOk()
            ->assertSeeInOrder([
                'Tentative Bookings',
                $bookingSummary,
            ])
            ->assertDontSee(
                $otherBookingSummary
            );
    }

    public function test_team_member_cannot_see_tentative_bookings(): void
    {
        $user = User::factory()->teamMember()->create();

        $this
            ->actingAs($user)
            ->get('/booking/tentative')
            ->assertForbidden();
    }

    public function test_guest_cannot_see_tentative_bookings(): void
    {
        $user = User::factory()->guest()->create();

        $this
            ->actingAs($user)
            ->get('/booking/tentative')
            ->assertForbidden();
    }

    public function test_tentative_bookings_is_auth_protected(): void
    {
        $this
            ->get('/booking/tentative')
            ->assertRedirect('/login');
    }
}
