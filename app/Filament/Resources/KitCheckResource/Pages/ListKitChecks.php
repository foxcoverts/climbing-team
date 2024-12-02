<?php

namespace App\Filament\Resources\KitCheckResource\Pages;

use App\Filament\Resources\KitCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKitChecks extends ListRecords
{
    protected static string $resource = KitCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Log kit check'),
        ];
    }
}
