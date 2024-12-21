<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Events\Registered;
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
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
