<?php

namespace App\Filament\Resources\QualificationResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\QualificationResource;
use App\Models\Qualification;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListQualifications extends ListRecords
{
    protected static string $resource = QualificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('admin')
                ->link()
                ->icon(Admin::getNavigationIcon())
                ->url(fn (): string => Admin\Resources\QualificationResource::getUrl())
                ->visible(fn (): bool => Gate::check('viewAny', Qualification::class)),
        ];
    }
}
