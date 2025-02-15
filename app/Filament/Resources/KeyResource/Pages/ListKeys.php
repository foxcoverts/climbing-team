<?php

namespace App\Filament\Resources\KeyResource\Pages;

use App\Filament\Resources\KeyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKeys extends ListRecords
{
    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
