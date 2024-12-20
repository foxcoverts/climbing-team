<?php

namespace App\Filament\Pages;

use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use RalphJSmit\Filament\Activitylog\Infolists\Components\Timeline;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bars-arrow-down';

    protected static string $view = 'filament.pages.activity-log';

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
