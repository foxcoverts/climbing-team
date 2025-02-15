<?php

namespace App\Filament\Clusters\Admin\Resources\UserResource\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count()),
            Stat::make('Active Users', User::whereNot('password', '')->count()),
            Stat::make('Permit Holders', User::whereHas('scoutPermits')->count()),
        ];
    }
}
