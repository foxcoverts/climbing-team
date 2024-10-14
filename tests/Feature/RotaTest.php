<?php

namespace Tests\Feature;

use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_rota_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();
        $booking->attendees()->syncWithPivotValues(
            $user,
            ['status' => BookingAttendeeStatus::Accepted],
        );
        $bookingSummary = __(':activity for :group at :location', [
            'activity' => $booking->activity,
            'group' => $booking->group_name,
            'location' => $booking->location,
        ]);

        $otherBooking = Booking::factory()->create();
        $otherBookingSummary = __(':activity for :group at :location', [
            'activity' => $otherBooking->activity,
            'group' => $otherBooking->group_name,
            'location' => $otherBooking->location,
        ]);

        $this
            ->actingAs($user)
            ->get('/rota')
            ->assertOk()
            ->assertViewIs('booking.rota.index')
            ->assertSeeInOrder([
                'My Rota', // Sidebar
                'My Rota', // Header
                $bookingSummary,
            ])
            ->assertDontSee(
                $otherBookingSummary
            );
    }

    public function test_rota_is_auth_protected(): void
    {
        $this
            ->get('/rota')
            ->assertSessionHasNoErrors()
            ->assertRedirect('/login');
    }

    public function test_next_booking_is_displayed_on_dashboard(): void
    {

        $user = User::factory()->create();
        $booking = Booking::factory()->create();
        $booking->attendees()->syncWithPivotValues(
            $user,
            ['status' => BookingAttendeeStatus::Accepted],
        );
        $bookingSummary = __(':activity for :group', [
            'activity' => $booking->activity,
            'group' => $booking->group_name,
        ]);

        $otherBooking = Booking::factory()->create();
        $otherBookingSummary = __(':activity for :group', [
            'activity' => $otherBooking->activity,
            'group' => $otherBooking->group_name,
        ]);

        $this
            ->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeInOrder([
                'Next Booking',
                $bookingSummary,
            ])
            ->assertDontSee(
                $otherBookingSummary
            );
    }

    public function test_no_next_booking_is_displayed_on_dashboard_when_there_is_none(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $this
            ->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertDontSee('Next Booking');
    }
}
