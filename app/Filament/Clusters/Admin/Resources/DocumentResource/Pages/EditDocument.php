<?php

namespace App\Filament\Clusters\Admin\Resources\DocumentResource\Pages;

use App\Filament\Clusters\Admin\Resources\DocumentResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog;

class EditDocument extends EditRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Activitylog\Actions\TimelineAction::make()
                ->label('Log')
                ->color('info'),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
