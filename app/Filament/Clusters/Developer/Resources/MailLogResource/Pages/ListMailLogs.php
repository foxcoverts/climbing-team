<?php

namespace App\Filament\Clusters\Developer\Resources\MailLogResource\Pages;

use App\Filament\Clusters\Developer\Resources\MailLogResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Resources\Pages\ListRecords;

class ListMailLogs extends ListRecords
{
    use HasClusterSidebarNavigation;

    protected static string $resource = MailLogResource::class;

    public function getSubNavigation(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
