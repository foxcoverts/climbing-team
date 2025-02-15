<?php

namespace App\Filament\Clusters\Admin\Resources\BookingResource\Pages;

use App\Filament\Clusters\Admin\Resources\BookingResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreateBooking extends CreateRecord
{
    use Concerns\MutatesFormData, HasClusterSidebarNavigation;

    protected static string $resource = BookingResource::class;
}
