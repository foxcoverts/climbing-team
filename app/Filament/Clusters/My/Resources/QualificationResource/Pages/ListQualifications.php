<?php

namespace App\Filament\Clusters\My\Resources\QualificationResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Clusters\My\Resources\QualificationResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Models\Qualification;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListQualifications extends ListRecords
{
    use HasClusterSidebarNavigation;

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
