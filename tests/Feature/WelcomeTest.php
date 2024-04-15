<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WelcomeTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_welcome_page_is_displayed(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }
}
