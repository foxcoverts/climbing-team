<?php

namespace App\Filament\Clusters\Admin\Resources\NewsPostResource\Pages;

use App\Filament\Clusters\Admin\Resources\NewsPostResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreateNewsPost extends CreateRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = NewsPostResource::class;
}
