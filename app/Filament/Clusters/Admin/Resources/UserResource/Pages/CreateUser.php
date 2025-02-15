<?php

namespace App\Filament\Clusters\Admin\Resources\UserResource\Pages;

use App\Events\Registered;
use App\Filament\Clusters\Admin\Resources\UserResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    use HasClusterSidebarNavigation;

    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['password'] = '';

        return $data;
    }

    protected function afterCreate(): void
    {
        event(new Registered($this->record));
    }
}
