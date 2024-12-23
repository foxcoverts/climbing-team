<?php

namespace App\Filament\Resources\QualificationResource\Pages;

use App\Filament\Resources\QualificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListQualifications extends ListRecords
{
    protected static string $resource = QualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            QualificationResource\Widgets\QualificationTypeChart::make(),
        ];
    }
}
