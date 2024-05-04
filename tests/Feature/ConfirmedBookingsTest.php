<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConfirmedBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_leader_can_see_confirmed_bookings(): void
    {
        $user = User::factory()->teamLeader()->create();
        $booking = Booking::factory()->confirmed()->create();
        $bookingSummary = __(':activity for :group at :location', [
            'activity' => $booking->activity,
            'group' => $booking->group_name,
            'location' => $booking->location,
        ]);

        $otherBooking = Booking::factory()->tentative()->create();
        $otherBookingSummary = __(':activity for :group at :location', [
            'activity' => $otherBooking->activity,
            'group' => $otherBooking->group_name,
            'location' => $otherBooking->location,
        ]);

        $this
            ->actingAs($user)
            ->get('/booking/confirmed')
            ->assertOk()
            ->assertSeeInOrder([
                'Confirmed Bookings',
                $bookingSummary,
            ])
            ->assertDontSee(
                $otherBookingSummary
            );
    }

    public function test_team_member_can_see_confirmed_bookings(): void
    {
        $user = User::factory()->teamMember()->create();
        $booking = Booking::factory()->confirmed()->create();
        $bookingSummary = __(':activity for :group at :location', [
            'activity' => $booking->activity,
            'group' => $booking->group_name,
            'location' => $booking->location,
        ]);

        $otherBooking = Booking::factory()->tentative()->create();
        $otherBookingSummary = __(':activity for :group at :location', [
            'activity' => $otherBooking->activity,
            'group' => $otherBooking->group_name,
            'location' => $otherBooking->location,
        ]);

        $this
            ->actingAs($user)
            ->get('/booking/confirmed')
            ->assertOk()
            ->assertSeeInOrder([
                'Confirmed Bookings',
                $bookingSummary,
            ])
            ->assertDontSee(
                $otherBookingSummary
            );
    }

    public function test_guest_cannot_see_confirmed_bookings(): void
    {
        $user = User::factory()->guest()->create();

        $this
            ->actingAs($user)
            ->get('/booking/confirmed')
            ->assertForbidden();
    }

    public function test_confirmed_bookings_is_auth_protected(): void
    {
        $this
            ->get('/booking/confirmed')
            ->assertRedirect('/login');
    }
}
