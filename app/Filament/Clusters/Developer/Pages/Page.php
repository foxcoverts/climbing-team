<?php

namespace App\Filament\Clusters\Developer\Pages;

use App\Filament\Clusters\Developer;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Pages\Page as FilamentPage;

abstract class Page extends FilamentPage
{
    use HasClusterSidebarNavigation;

    protected static ?string $cluster = Developer::class;

    protected static ?string $navigationGroup = 'Developer';
}
