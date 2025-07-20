<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CalendarTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_leader_can_see_calendar(): void
    {
        $user = User::factory()->teamLeader()->create();

        $this
            ->actingAs($user)
            ->get('/booking')
            ->assertOk()
            ->assertViewIs('booking.calendar');
    }

    public function test_team_member_can_see_calendar(): void
    {
        $user = User::factory()->teamMember()->create();

        $this
            ->actingAs($user)
            ->get('/booking')
            ->assertOk()
            ->assertViewIs('booking.calendar');
    }

    public function test_guest_cannot_see_calendar(): void
    {
        $user = User::factory()->guest()->create();

        $this
            ->actingAs($user)
            ->get('/booking')
            ->assertForbidden();
    }

    public function test_suspended_cannot_see_calendar(): void
    {
        $user = User::factory()->suspended()->create();

        $this
            ->actingAs($user)
            ->get('/booking')
            ->assertForbidden();
    }

    public function test_calendar_is_auth_protected(): void
    {
        $this
            ->get('/booking')
            ->assertRedirect('/login');
    }
}
