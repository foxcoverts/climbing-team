<?php

namespace Tests\Feature;

use App\Models\NewsPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_news_page_is_displayed(): void
    {
        $user = User::factory()->create();
        $postA = NewsPost::factory()->for($user, 'author')->create();
        $postB = NewsPost::factory()->for($user, 'author')->create();

        $this
            ->actingAs($user)
            ->get('/news')
            ->assertOk()
            ->assertViewIs('news.index')
            ->assertSeeInOrder([
                $postA->title,
                'Read more...',
                $postB->title,
                'Read more...',
            ]);
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
        $author = User::factory()->create();
        $post = NewsPost::factory()->for($author, 'author')->create();

        $this
            ->get('/news/'.$post->slug)
            ->assertOk()
            ->assertSee($post->title)
            ->assertSee('Please login to view the full post.');
    }

    public function test_news_article_can_be_seen_in_full_when_authed(): void
    {
        $user = User::factory()->create();
        $post = NewsPost::factory()->for($user, 'author')->create();

        $this
            ->actingAs($user)
            ->get('/news/'.$post->slug)
            ->assertOk()
            ->assertSee($post->title)
            ->assertDontSee('Please login to view the full post.');
    }

    public function test_news_is_displayed_on_dashboard(): void
    {
        $user = User::factory()->create();
        $post = NewsPost::factory()->for($user, 'author')->create();

        $this
            ->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertSeeInOrder([
                'Recent News',
                $post->title,
                'Posted',
                'View all news',
            ]);
    }
}
