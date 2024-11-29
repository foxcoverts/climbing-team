<?php

namespace App\Filament\Resources\KeyResource\Pages;

use App\Events\KeyTransferred;
use App\Filament\Resources\KeyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditKey extends EditRecord
{
    protected static string $resource = KeyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $lastHolder = $record->holder;

        $record->update($data);

        if ($record->wasChanged('holder_id')) {
            $record->refresh();
            event(new KeyTransferred($record, from: $lastHolder));
        }

        return $record;
    }
}
