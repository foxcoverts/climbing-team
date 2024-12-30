<?php

namespace App\Filament\Clusters\My\Pages;

use App\Filament\Clusters\My;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Pages\Page as FilamentPage;

abstract class Page extends FilamentPage
{
    use HasClusterSidebarNavigation;

    protected static ?string $cluster = My::class;

    public static function getNavigationGroup(): ?string
    {
        return static::$cluster::getNavigationLabel();
    }
}
