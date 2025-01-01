<?php

namespace App\Filament\Resources\KeyResource\Tables\Actions;

use App\Models\Key;
use App\Models\User;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Forms;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Gate;

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

        $this->visible(fn (Key $record): bool => Gate::check('transfer', $record));

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
            $this->process(function (array $data, Key $record) {
                if (! Gate::check('transfer', $record)) {
                    $this->failure();

                    return;
                }

                $record->holder()->associate($data['to']);
                $record->save();
            });

            $this->success();
        });
    }
}
