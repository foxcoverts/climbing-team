<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Filament\Clusters\Admin;
use Filament\Resources\Resource as FilamentResource;

abstract class Resource extends FilamentResource
{
    protected static ?string $cluster = Admin::class;

    public static function getNavigationGroup(): ?string
    {
        return static::$cluster::getNavigationLabel();
    }

    public static function canAccess(): bool
    {
        return static::can('manage');
    }
}
