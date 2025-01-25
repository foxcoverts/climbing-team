<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
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
}
