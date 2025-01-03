<?php

namespace App\Filament\Clusters\Admin\Resources\DocumentResource\Pages;

use App\Filament\Clusters\Admin\Resources\DocumentResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
