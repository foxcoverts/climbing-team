<?php

namespace App\Filament\Resources\NewsPostResource\Pages;

use App\Filament\Resources\NewsPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use RalphJSmit\Filament\Activitylog;

class ViewNewsPost extends ViewRecord
{
    protected static string $resource = NewsPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActivityLog\Actions\TimelineAction::make()
                ->label('Log')
                ->color('info'),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
