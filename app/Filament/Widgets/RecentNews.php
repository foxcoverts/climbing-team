<?php

namespace App\Filament\Widgets;

use App\Models\NewsPost;
use Carbon\Carbon;
use Filament\Widgets\Widget;

class RecentNews extends Widget
{
    protected static string $view = 'filament.widgets.recent-news';

    public static function canView(): bool
    {
        return filled(static::getMostRecentPost());
    }

    protected function getViewData(): array
    {
        $post = static::getMostRecentPost();

        return [
            'title' => $post->title,
            'author' => $post->author->name,
            'ago' => localDate($post->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]),
            'summary' => $post->summary,
            'body' => $post->body,
            'link' => route('news.show', $post),
        ];
    }

    protected static function getMostRecentPost(): ?NewsPost
    {
        return NewsPost::orderByDesc('created_at')->first();
    }
}
