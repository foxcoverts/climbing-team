<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Filament\Clusters\Admin;
use Filament\Resources\Resource as FilamentResource;

abstract class Resource extends FilamentResource
{
    protected static ?string $cluster = Admin::class;

    protected static ?string $navigationGroup = 'Admin';
}
