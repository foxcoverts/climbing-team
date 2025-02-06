<?php

namespace App\Filament\Resources\NewsPostResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\NewsPostResource;
use Filament\Actions;
use Filament\Panel;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class ViewNewsPost extends ViewRecord
{
    protected static string $resource = NewsPostResource::class;

    protected static string|array $withoutRouteMiddleware = ['auth'];

    public static function authorizeResourceAccess(): void {}

    public static function isEmailVerificationRequired(Panel $panel): bool
    {
        return false;
    }

    public function render(): View
    {
        $record = $this->getRecord();
        $title = $record->title;
        $description = $record->summaryText;
        $updated = localDate($record->updated_at)->toIso8601String();
        $image = asset('images/news/fox-coverts-climbing-necker.png');
        $image_width = 744;
        $image_height = 328;

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => implode('', [
                '<meta property="og:site_name" content="'.config('app.name', 'Climbing Team').'" />',
                '<meta property="og:title" content="'.$title.'" />',
                '<meta property="twitter:title" content="'.$title.'" />',
                '<meta name="description" content="'.$description.'" />',
                '<meta property="og:description" content="'.$description.'" />',
                '<meta name="twitter:description" content="'.$description.'" />',
                '<meta property="og:updated_time" content="'.$updated.'" />',

                '<meta property="og:image" content="'.$image.'" />',
                '<meta name="twitter:image" content="'.$image.'" />',
                '<meta property="og:image:width" content="'.$image_width.'" />',
                '<meta property="og:image:height" content="'.$image_height.'" />',

                '<meta property="og:type" content="website" />',
                '<meta property="og:locale" content="'.config('app.locale', 'en').'" />',
                '<meta name="twitter:card" content="summary" />',
                '<meta name="twitter:site" content="'.config('app.twitter', '@scouts').'" />',
            ]),
            scopes: static::class,
        );

        return parent::render();
    }

    public function getHeading(): string|Htmlable
    {
        return $this->getRecordTitle();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (): string => Admin\Resources\NewsPostResource::getUrl('edit', ['record' => $this->getRecord()]))
                    ->visible(fn (): bool => Gate::check('update', $this->getRecord())),
            ]),
        ];
    }
}
