<?php

namespace App\Filament\Resources\KeyResource\Tables\Actions;

use App\Events\KeyTransferred;
use App\Models\Key;
use App\Models\User;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Illuminate\Http\Request;

class TransferAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'transfer';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Transfer');

        $this->modalHeading(fn (): string => __('Transfer :label', ['label' => $this->getRecordTitle()]));

        $this->modalSubmitActionLabel('Transfer');

        $this->successNotificationTitle('Transferred');

        $this->icon('heroicon-m-arrows-right-left');

        $this->visible(fn (Request $request, Key $record): bool => $request->user()->can('transfer', $record));

        $this->form([
            Forms\Components\Placeholder::make('from')
                ->content(fn (Key $record): string => $record->holder->name),
            Forms\Components\Select::make('to')
                ->label('To')
                ->options(fn (Key $record) => User::whereNot('id', $record->holder_id)->pluck('name', 'id'))
                ->searchable()
                ->preload()
                ->notIn(fn (Key $record) => $record->holder_id)
                ->required(),
        ]);

        $this->fillForm(fn (Key $record): array => $record->attributesToArray());

        $this->action(function (): void {
            $this->process(function (array $data, Key $record, Request $request) {
                if (! $request->user()->can('transfer', $record)) {
                    $this->failure();

                    return;
                }

                $lastHolder = $record->holder;

                $record->update([
                    'holder_id' => $data['to'],
                ]);

                if ($record->wasChanged('holder_id')) {
                    $record->refresh();
                    event(new KeyTransferred($record, from: $lastHolder));
                }
            });

            $this->success();
        });
    }
}
