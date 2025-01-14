<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Clusters\Admin;
use App\Filament\Resources\DocumentResource;
use App\Models\Document;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Gate;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('admin')
                ->link()
                ->icon(Admin::getNavigationIcon())
                ->url(fn (): string => Admin\Resources\DocumentResource::getUrl())
                ->visible(fn (): bool => Gate::check('manage', Document::class)),
        ];
    }
}
