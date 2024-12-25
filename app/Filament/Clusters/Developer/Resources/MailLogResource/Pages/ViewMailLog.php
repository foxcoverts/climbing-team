<?php

namespace App\Filament\Clusters\Developer\Resources\MailLogResource\Pages;

use App\Filament\Clusters\Developer\Resources\MailLogResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMailLog extends ViewRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = MailLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function mount(int|string $record): void
    {
        parent::mount($record);

        $this->record->markRead()->save();
    }
}
