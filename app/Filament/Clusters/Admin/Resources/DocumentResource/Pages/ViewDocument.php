<?php

namespace App\Filament\Clusters\Admin\Resources\DocumentResource\Pages;

use App\Filament\Clusters\Admin\Resources\DocumentResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Models\Document;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Storage;

class ViewDocument extends ViewRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('download')
                ->color('info')
                ->icon('heroicon-m-arrow-down-tray')
                ->action(fn (Document $record) => Storage::response($record->file_path, $record->file_name)),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
