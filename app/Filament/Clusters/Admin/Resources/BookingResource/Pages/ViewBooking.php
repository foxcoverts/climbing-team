<?php

namespace App\Filament\Clusters\Admin\Resources\BookingResource\Pages;

use App\Filament\Clusters\Admin\Resources\BookingResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
