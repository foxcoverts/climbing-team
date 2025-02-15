<?php

namespace App\Filament\Clusters\Admin\Resources\KeyResource\Pages;

use App\Filament\Clusters\Admin\Resources\KeyResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog;

class EditKey extends EditRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Activitylog\Actions\TimelineAction::make()
                ->label('Log')
                ->color('info'),
            Actions\DeleteAction::make(),
        ];
    }
}
