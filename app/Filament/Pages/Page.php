<?php

namespace App\Filament\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Page as FilamentPage;

abstract class Page extends FilamentPage
{
    // public static function registerNavigationItems(): void
    // {
    //     if (! static::shouldRegisterNavigation()) {
    //         return;
    //     }

    //     if (! static::canAccess()) {
    //         return;
    //     }

    //     Filament::getCurrentPanel()
    //         ->navigationItems(static::getNavigationItems());
    // }
}
