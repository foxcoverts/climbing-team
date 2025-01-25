<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Enums\BookingAttendeeStatus;
use App\Filament\Clusters\Admin;
use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('admin')
                ->link()
                ->icon(Admin::getNavigationIcon())
                ->url(fn (): string => Admin\Resources\BookingResource::getUrl())
                ->visible(fn (): bool => Gate::check('viewAny', Booking::class)),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make()->label('All')
                ->modifyQueryUsing(fn (Eloquent\Builder $query) => $query
                    ->forUser(Auth::user())
                ),
            ...collect(BookingAttendeeStatus::cases())
                ->mapWithKeys(fn (BookingAttendeeStatus $status) => [
                            $status->value => Tab::make()
                                ->label($status->getLabel())
                                ->icon($status->getIcon())
                                ->badge(fn () => Booking::future()->attendeeStatus(Auth::user(), $status)->count())
                                ->badgeColor($status->getColor())
                                ->modifyQueryUsing(fn (Eloquent\Builder $query) => $query
                                    ->attendeeStatus(Auth::user(), $status)
                                ),
                        ])
                ->all(),
        ];
    }

    public function getDefaultActiveTab(): string|int|null
    {
        return BookingAttendeeStatus::Accepted->value;
    }
}
