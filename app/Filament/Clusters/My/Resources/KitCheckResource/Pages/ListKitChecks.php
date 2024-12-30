<?php

namespace App\Filament\Clusters\My\Resources\KitCheckResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Clusters\My\Resources\KitCheckResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Models\KitCheck;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListKitChecks extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = KitCheckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('admin')
                ->link()
                ->icon(Admin::getNavigationIcon())
                ->url(fn (): string => Admin\Resources\KitCheckResource::getUrl())
                ->visible(fn (): bool => Gate::check('viewAny', KitCheck::class)),
        ];
    }
}
