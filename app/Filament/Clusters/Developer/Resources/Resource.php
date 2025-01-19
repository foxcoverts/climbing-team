<?php

namespace App\Filament\Clusters\Developer\Resources;

use App\Filament\Clusters\Developer;
use Filament\Resources\Resource as FilamentResource;

abstract class Resource extends FilamentResource
{
    protected static ?string $cluster = Developer::class;

    public static function getNavigationGroup(): ?string
    {
        return static::$cluster::getNavigationLabel();
    }
}
