<?php

namespace App\Filament\Clusters\Developer\Pages;

use App\Filament\Clusters\Developer;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Filament\Pages\Page as AppPage;

abstract class Page extends AppPage
{
    use HasClusterSidebarNavigation;

    protected static ?string $cluster = Developer::class;

    protected static ?string $navigationGroup = 'Developer';
}
