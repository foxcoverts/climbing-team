<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Actions\RespondAction;
use App\Filament\Clusters\Admin;
use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected static string|array $withoutRouteMiddleware = ['auth'];

    public static function authorizeResourceAccess(): void
    {
        if (Filament::auth()->check()) {
            parent::authorizeResourceAccess();
        }
    }

    public static function isEmailVerificationRequired(Panel $panel): bool
    {
        return false;
    }

    public function render(): View
    {
        $record = $this->getRecord();
        $startAt = $record->start_at->timezone($record->timezone);
        $endAt = $record->end_at->timezone($record->timezone);

        $title = __(':activity on :day', [
            'activity' => $record->activity,
            'day' => $startAt->toFormattedDayDateString(),
        ]);
        $description = __(':activity from :start_time to :end_time at :location.', [
            'activity' => $record->activity,
            'start_time' => $startAt->format('H:i'),
            'end_time' => $endAt->format('H:i'),
            'location' => $record->location,
        ]);
        $updated = $record->updated_at->toIso8601String();
        $image = asset('images/dates/'.$startAt->format('n/n-j').'.png');
        $image_width = 700;
        $image_height = 700;

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

    protected function getHeaderActions(): array
    {
        return [
            RespondAction::make()
                ->useStatusLabel()
                ->record($this->getRecord()),
            Actions\ActionGroup::make([
                Actions\Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (): string => Admin\Resources\BookingResource::getUrl('edit', ['record' => $this->getRecord()]))
                    ->visible(fn (): bool => Gate::check('update', $this->getRecord())),
            ]),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Booking Details';
    }
}
