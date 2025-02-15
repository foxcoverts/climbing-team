<?php

namespace App\Filament\Clusters\Admin\Resources\NewsPostResource\Pages;

use App\Filament\Clusters\Admin\Resources\NewsPostResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog;

class EditNewsPost extends EditRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = NewsPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActivityLog\Actions\TimelineAction::make()
                ->label('Log')
                ->color('info'),
            Actions\DeleteAction::make(),
        ];
    }
}
