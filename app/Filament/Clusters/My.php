<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;
use Illuminate\Contracts\View\View;

class My extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-user';

    public function render(): View
    {
        // Will only get here if there are no accessible resources/pages.
        abort(403);
    }
}
