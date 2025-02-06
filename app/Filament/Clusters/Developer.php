<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Illuminate\Contracts\View\View;

class Developer extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-server-stack';

    public function render(): View
    {
        // Will only get here if there are no accessible resources/pages.
        abort(403);
    }
}
