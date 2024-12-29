<?php

namespace App\Filament\Clusters\Admin\Resources\KeyResource\Pages;

use App\Filament\Clusters\Admin\Resources\KeyResource;
use App\Filament\Clusters\My;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Models\Key;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListKeys extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('my')
                ->label('My Keys')
                ->link()
                ->icon(My::getNavigationIcon())
                ->url(fn (): string => My\Resources\KeyResource::getUrl())
                ->visible(fn (): bool => Gate::check('viewOwn', Key::class)),
            Actions\CreateAction::make(),
        ];
    }
}
