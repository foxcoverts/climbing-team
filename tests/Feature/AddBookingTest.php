<?php

namespace Tests\Feature;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddBookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_leader_can_see_add_booking_form(): void
    {
        $user = User::factory()->teamLeader()->create();

        $this
            ->actingAs($user)
            ->get('/booking/create')
            ->assertOk()
            ->assertSeeInOrder([
                'Add Booking', // Sidebar
                'Add Booking', // Header
            ]);
    }

    public function test_validates_add_booking(): void
    {
        $user = User::factory()->teamLeader()->create();

        $this
            ->actingAs($user)
            ->get('/booking/create');
        $this
            ->actingAs($user)
            ->post('/booking', [])
            ->assertRedirect('/booking/create')
            ->assertSessionHasErrors();
    }

    public function test_can_add_booking(): void
    {
        $user = User::factory()->teamLeader()->create();

        $response = $this
            ->actingAs($user)
            ->post('/booking', [
                'start_date' => '2024-04-15',
                'start_time' => '10:00',
                'end_time' => '14:00',
                'timezone' => 'Europe/London',
                'location' => 'Fox Coverts Campsite',
                'activity' => 'Climbing',
                'group_name' => '1st Anytown Scouts',
                'notes' => 'x20 Young People',
            ]);

        $booking = Booking::latest()->first();

        $response->assertRedirectToRoute('booking.show', $booking);

        $this->assertEquals(BookingStatus::Tentative, $booking->status);
        $this->assertEquals('2024-04-15 09:00', $booking->start_at->format('Y-m-d H:i')); // UTC
        $this->assertEquals('2024-04-15 13:00', $booking->end_at->format('Y-m-d H:i')); // UTC
        $this->assertEquals('Europe/London', $booking->timezone->getName());
        $this->assertEquals('Fox Coverts Campsite', $booking->location);
        $this->assertEquals('Climbing', $booking->activity);
        $this->assertEquals('1st Anytown Scouts', $booking->group_name);
        $this->assertEquals('x20 Young People', $booking->notes);
    }

    public function test_team_member_cannot_add_booking(): void
    {
        $user = User::factory()->teamMember()->create();

        $this
            ->actingAs($user)
            ->get('/booking/create')
            ->assertForbidden();
    }

    public function test_guest_cannot_add_booking(): void
    {
        $user = User::factory()->guest()->create();

        $this
            ->actingAs($user)
            ->get('/booking/create')
            ->assertForbidden();
    }

    public function test_suspended_cannot_add_booking(): void
    {
        $user = User::factory()->suspended()->create();

        $this
            ->actingAs($user)
            ->get('/booking/create')
            ->assertForbidden();
    }

    public function test_add_booking_is_auth_protected(): void
    {
        $this
            ->get('/booking/create')
            ->assertRedirect('/login');
    }
}
