<?php

namespace App\Filament\Resources\KeyResource\Pages;

use App\Filament\Resources\KeyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog;

class EditKey extends EditRecord
{
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
