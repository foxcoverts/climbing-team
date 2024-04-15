<?php

namespace Tests\Feature;

use App\Models\User;
use App\Repositories\NewsPostRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $this
            ->actingAs($user)
            ->get('/news')
            ->assertOk()
            ->assertViewIs('news.index');
    }

    public function test_news_is_auth_protected(): void
    {
        $this
            ->get('/news')
            ->assertSessionHasNoErrors()
            ->assertRedirect('/login');
    }

    public function test_news_article_can_be_previewed(): void
    {
        $post = (new NewsPostRepository)->first();

        $this
            ->get('/news/'.$post->filename)
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee('Please login to view the full post.');
    }

    public function test_news_article_can_be_seen_in_full_when_authed(): void
    {
        $user = User::factory()->create();
        $post = (new NewsPostRepository)->first();

        $this
            ->actingAs($user)
            ->get('/news/'.$post->filename)
            ->assertOk()
            ->assertSee($post->title)
            ->assertDontSee('Please login to view the full post.');
    }

    public function test_news_is_displayed_on_dashboard(): void
    {
        $user = User::factory()->create();
        $post = (new NewsPostRepository)->first();

        $this
            ->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeInOrder([
                'Recent News',
                $post->title,
                'View all news',
            ]);
    }
}
