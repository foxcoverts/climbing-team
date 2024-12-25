<?php

namespace App\Filament\Clusters\Admin\Resources\KitCheckResource\Pages;

use App\Filament\Clusters\Admin\Resources\KitCheckResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKitChecks extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = KitCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Log kit check'),
        ];
    }
}
