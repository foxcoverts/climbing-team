<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use RalphJSmit\Filament\Activitylog;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('re-send-invite')
                ->label('Re-send Invite')
                ->hidden(fn (User $record) => $record->isActive())
                ->requiresConfirmation()
                ->action(function (User $record) {
                    $record->sendAccountSetupNotification();

                    Notification::make()
                        ->title('Invite sent')
                        ->success()
                        ->send();
                }),

            Activitylog\Actions\TimelineAction::make()
                ->label('Log')->color('info'),
            Actions\DeleteAction::make(),
        ];
    }
}
