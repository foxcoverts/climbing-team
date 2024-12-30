<?php

namespace App\Filament\Clusters\My\Resources;

use App\Filament\Clusters\My;
use Filament\Resources\Resource as FilamentResource;

abstract class Resource extends FilamentResource
{
    protected static ?string $cluster = My::class;

    public static function getNavigationGroup(): ?string
    {
        return static::$cluster::getNavigationLabel();
    }

    public static function canViewAny(): bool
    {
        return static::can('viewOwn');
    }

    public static function authorizeViewAny(): void
    {
        static::authorize('viewOwn');
    }
}
