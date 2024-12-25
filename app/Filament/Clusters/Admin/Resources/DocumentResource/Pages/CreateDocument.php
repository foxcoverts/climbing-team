<?php

namespace App\Filament\Clusters\Admin\Resources\DocumentResource\Pages;

use App\Filament\Clusters\Admin\Resources\DocumentResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreateDocument extends CreateRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = DocumentResource::class;
}
