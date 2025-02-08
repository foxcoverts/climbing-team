<?php

namespace App\Filament\Resources\KeyResource\Pages;

use App\Filament\Resources\KeyResource;
use App\Filament\Resources\KeyResource\Actions\TransferAction;
use Filament\Resources\Pages\ViewRecord;
use RalphJSmit\Filament\Activitylog;

class ViewKey extends ViewRecord
{
    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Activitylog\Actions\TimelineAction::make()
                ->label('Log')
                ->color('info'),
            TransferAction::make(),
        ];
    }
}
