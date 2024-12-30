<?php

namespace App\Filament\Clusters\My\Resources\QualificationResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Clusters\My\Resources\QualificationResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Gate;

class ViewQualification extends ViewRecord
{
    use HasClusterSidebarNavigation;

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
