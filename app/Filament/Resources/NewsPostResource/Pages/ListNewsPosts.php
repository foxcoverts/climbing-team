<?php

namespace App\Filament\Resources\NewsPostResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\NewsPostResource;
use App\Models\NewsPost;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListNewsPosts extends ListRecords
{
    protected static string $resource = NewsPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('admin')
                ->link()
                ->icon(Admin::getNavigationIcon())
                ->url(fn (): string => Admin\Resources\NewsPostResource::getUrl())
                ->visible(fn (): bool => Gate::check('manage', NewsPost::class)),
        ];
    }
}
