<?php

namespace App\Filament\Pages\Concerns;

use Filament\Facades\Filament;

trait HasClusterSidebarNavigation
{
    public function mountHasClusterSidebarNavigation(): void
    {
        if (filled($cluster = static::getCluster())) {
            Filament::getCurrentPanel()
                ->navigationItems($this->generateNavigationItems($cluster::getClusteredComponents()));
        }
    }

    public function getSubNavigation(): array
    {
        return [];
    }
}
