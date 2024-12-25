<?php

namespace App\Filament\Clusters\Admin\Resources\NewsPostResource\Pages;

use App\Filament\Clusters\Admin\Resources\NewsPostResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsPosts extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = NewsPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New post'),
        ];
    }
}
