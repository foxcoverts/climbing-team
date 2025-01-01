<?php

namespace App\Filament\Resources\QualificationResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\QualificationResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;

class ViewQualification extends ViewRecord
{
    protected static string $resource = QualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\Action::make('edit')
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (): string => Admin\Resources\QualificationResource::getUrl('edit', ['record' => $this->getRecord()]))
                    ->visible(fn (): bool => Gate::check('update', $this->getRecord())),
            ]),
        ];
    }
}
