<?php

namespace App\Filament\Clusters\Admin\Resources\QualificationResource\Pages;

use App\Filament\Clusters\Admin\Resources\QualificationResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQualifications extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = QualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            QualificationResource\Widgets\QualificationTypeChart::make(),
        ];
    }
}
