<?php

namespace App\Filament\Clusters\Admin\Resources\QualificationResource\Pages;

use App\Filament\Clusters\Admin\Resources\QualificationResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewQualification extends ViewRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = QualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
