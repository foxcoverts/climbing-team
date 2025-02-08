<?php

namespace App\Filament\Resources\KeyResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\KeyResource;
use App\Models\Key;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListKeys extends ListRecords
{
    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('admin')
                ->link()
                ->icon(Admin::getNavigationIcon())
                ->url(fn (): string => Admin\Resources\KeyResource::getUrl())
                ->visible(fn (): bool => Gate::check('viewAny', Key::class)),
        ];
    }
}
