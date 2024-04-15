<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeletedBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_leader_can_see_deleted_bookings(): void
    {
        $user = User::factory()->teamLeader()->create();
        $booking = Booking::factory()->cancelled()->create();
        $booking->delete();
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
            ->get('/trash/booking')
            ->assertOk()
            ->assertSeeInOrder([
                'Deleted Bookings',
                $bookingSummary,
            ], escape: false)
            ->assertDontSee(
                $otherBookingSummary,
                escape: false
            );
    }

    public function test_restore_deleted_booking(): void
    {
        $user = User::factory()->teamLeader()->create();
        $booking = Booking::factory()->cancelled()->create();
        $booking->delete();

        $this
            ->actingAs($user)
            ->put('/trash/booking/'.$booking->id, [
                'deleted_at' => false,
            ])
            ->assertRedirectToRoute('booking.show', $booking);

        $booking->refresh();

        $this->assertNull($booking->deleted_at);
        $this->assertTrue($booking->isCancelled(), 'Booking is Cancelled');
    }

    public function test_team_member_cannot_see_deleted_bookings(): void
    {
        $user = User::factory()->teamMember()->create();

        $this
            ->actingAs($user)
            ->get('/trash/booking')
            ->assertForbidden();
    }

    public function test_guest_cannot_see_deleted_bookings(): void
    {
        $user = User::factory()->guest()->create();

        $this
            ->actingAs($user)
            ->get('/trash/booking')
            ->assertForbidden();
    }

    public function test_deleted_bookings_is_auth_protected(): void
    {
        $this
            ->get('/trash/booking')
            ->assertRedirect('/login');
    }
}
