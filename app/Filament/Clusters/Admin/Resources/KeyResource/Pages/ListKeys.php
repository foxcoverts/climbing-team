<?php

namespace App\Filament\Clusters\Admin\Resources\KeyResource\Pages;

use App\Filament\Clusters\Admin\Resources\KeyResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeys extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
