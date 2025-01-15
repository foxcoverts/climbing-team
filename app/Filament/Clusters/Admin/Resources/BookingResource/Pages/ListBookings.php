<?php

namespace App\Filament\Clusters\Admin\Resources\BookingResource\Pages;

use App\Enums\BookingStatus;
use App\Filament\Clusters\Admin\Resources\BookingResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Models\Booking;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent;

class ListBookings extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return collect(BookingStatus::cases())
            ->mapWithKeys(fn (BookingStatus $status) => [
                $status->value => Tab::make()
                    ->label($status->getLabel())
                    ->icon($status->getIcon())
                    ->badge(fn () => Booking::future()->where('status', $status->value)->count())
                    ->badgeColor($status->getColor())
                    ->modifyQueryUsing(fn (Eloquent\Builder $query) => $query->where('status', $status->value)),
            ])
            ->all();
    }
}
