<?php

namespace App\Filament\Clusters\Admin\Resources\BookingResource\Pages;

use App\Filament\Clusters\Admin\Resources\BookingResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBooking extends EditRecord
{
    use Concerns\MutatesFormData, HasClusterSidebarNavigation;

    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
