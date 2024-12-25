<?php

namespace App\Filament\Clusters\Developer\Pages;

use App\Filament\Clusters\Developer;
use Filament\Infolists\Infolist;
use RalphJSmit\Filament\Activitylog\Infolists\Components\Timeline;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Page
{
    protected static ?string $cluster = Developer::class;

    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';

    protected static string $view = 'filament.developer.pages.activity-log';

    public function timeline(Infolist $infolist): Infolist
    {
        return $infolist
            ->state([])
            ->schema([
                Timeline::make()
                    ->hiddenLabel()
                    ->getActivitiesUsing(fn () => Activity::all()),
            ]);
    }
}
