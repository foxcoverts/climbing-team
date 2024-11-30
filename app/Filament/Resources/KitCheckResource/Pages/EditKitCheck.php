<?php

namespace App\Filament\Resources\KitCheckResource\Pages;

use App\Filament\Resources\KitCheckResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKitCheck extends EditRecord
{
    protected static string $resource = KitCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
