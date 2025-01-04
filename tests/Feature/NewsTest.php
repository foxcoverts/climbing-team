<?php

namespace Tests\Feature;

use App\Filament\Pages\Dashboard;
use App\Filament\Resources\NewsPostResource;
use App\Filament\Resources\NewsPostResource\Pages\ListNewsPosts;
use App\Filament\Widgets\RecentNews;
use App\Models\NewsPost;
use App\Models\User;
use Filament\Facades\Filament;
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
            ->get(NewsPostResource::getUrl())
            ->assertOk()
            ->assertSeeLivewire(ListNewsPosts::class)
            ->assertSee($postA->title)
            ->assertSee($postB->title);
    }

    public function test_news_is_auth_protected(): void
    {
        $this
            ->get(NewsPostResource::getUrl())
            ->assertSessionHasNoErrors()
            ->assertRedirect(Filament::getCurrentPanel()->getLoginUrl());
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
            ->get(Dashboard::getUrl())
            ->assertOk()
            ->assertSeeLivewire(Dashboard::class)
            ->assertSeeLivewire(RecentNews::class);
    }
}
