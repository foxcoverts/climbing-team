<?php

namespace App\Filament\Resources\NewsPostResource\Pages;

use App\Filament\Resources\NewsPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListNewsPosts extends ListRecords
{
    protected static string $resource = NewsPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New post'),
        ];
    }
}
