<?php

namespace Tests\Feature;

use App\Enums\AttendeeStatus;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InviteTest extends TestCase
{
    use RefreshDatabase;

    public function test_invite_is_auth_protected(): void
    {
        $this
            ->get('/invite')
            ->assertSessionHasNoErrors()
            ->assertRedirect('/login');
    }

    public function test_invite_displays_needs_action_booking(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();
        $booking->attendees()->syncWithPivotValues(
            $user,
            ['status' => AttendeeStatus::NeedsAction]
        );
        $bookingSummary = __(':activity for :group at :location', [
            'activity' => $booking->activity,
            'group' => $booking->group_name,
            'location' => $booking->location,
        ]);

        $this
            ->actingAs($user)
            ->get('/invite')
            ->assertOk()
            ->assertSeeInOrder([
                'Invited',
                'You have been invited to the following bookings.',
                $bookingSummary,
            ], escape: false);
    }

    public function test_invite_displays_tentative_booking(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();
        $booking->attendees()->syncWithPivotValues(
            $user,
            ['status' => AttendeeStatus::Tentative],
        );
        $bookingSummary = __(':activity for :group at :location', [
            'activity' => $booking->activity,
            'group' => $booking->group_name,
            'location' => $booking->location,
        ]);

        $this
            ->actingAs($user)
            ->get('/invite')
            ->assertOk()
            ->assertSeeInOrder([
                'Maybe',
                'You have not yet confirmed that you can, or cannot, attend the following bookings.',
                $bookingSummary,
            ], escape: false);
    }

    public function test_invite_displays_on_dashboard(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();
        $booking->attendees()->syncWithPivotValues(
            $user,
            ['status' => AttendeeStatus::NeedsAction],
        );
        $bookingSummary = __(':activity for :group', [
            'activity' => $booking->activity,
            'group' => $booking->group,
        ]);

        $this
            ->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeInOrder([
                'My Invites',
                $bookingSummary,
                'View your invitations',
            ]);
    }

    public function test_dashboard_shows_no_invites_when_there_are_none(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create();

        $this
            ->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertDontSee('My Invites');
    }
}
