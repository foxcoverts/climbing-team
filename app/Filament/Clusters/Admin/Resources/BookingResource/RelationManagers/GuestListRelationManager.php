<?php

namespace App\Filament\Clusters\Admin\Resources\BookingResource\RelationManagers;

use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use App\Models\BookingAttendance;
use App\Models\User;
use App\Notifications\BookingInvite;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class GuestListRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $title = 'Guest List';

    public function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                Forms\Components\Select::make('status')
                    ->default(BookingAttendeeStatus::NeedsAction)
                    ->options(BookingAttendeeStatus::class)
                    ->disableOptionWhen(fn (string $value): bool => $value === BookingAttendeeStatus::NeedsAction->value)
                    ->selectablePlaceholder(false)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('keys', 'scoutPermits'))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\ViewColumn::make('badges')
                    ->view('filament.admin.resources.user-resource.columns.badges'),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(BookingAttendeeStatus::class),
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make('invite')
                    ->hidden(fn (RelationManager $livewire) => $livewire->getOwnerRecord()->isPast() || $livewire->getOwnerRecord()->isCancelled())
                    ->label('Invite')
                    ->modalHeading('Invite Attendees')
                    ->modalSubmitActionLabel('Invite Attendees')
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(fn (Builder $query) => $query->whereNotNull('email_verified_at'))
                    ->using(function (RelationManager $livewire, array $data) {
                        /** @var Booking $booking */
                        $booking = $livewire->getOwnerRecord();

                        if ($booking->isCancelled() || $booking->isPast()) {
                            return;
                        }

                        $invites = collect($data['recordId'])
                            ->reject(fn (string $id) => $booking->attendees->contains('id', $id))
                            ->flatMap(fn (string $id) => [
                                $id => [
                                    'status' => BookingAttendeeStatus::NeedsAction,
                                    'token' => BookingAttendance::generateToken(),
                                ],
                            ]);

                        $booking->attendees()->syncWithoutDetaching($invites);
                        $booking->load('attendees');

                        Notification::send(User::findMany($invites->keys()), new BookingInvite($booking));
                    })
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->multiple()
                            ->prefixIcon('heroicon-o-user')
                            ->placeholder('Select users')
                            ->hintAction(fn (Forms\Components\Select $component) => Forms\Components\Actions\Action::make('select all')
                                ->action(fn () => $component->state(array_keys($component->getOptions())))
                            )
                            ->helperText('Someone missing? Only users who have verified their email address will appear here. If you know their availability you may be able to add them directly.'),
                    ]),
                Tables\Actions\AttachAction::make()
                    ->label('Add')
                    ->modalHeading('Add Attendee')
                    ->modalSubmitActionLabel('Add Attendee')
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect()
                            ->prefixIcon('heroicon-o-user')
                            ->placeholder('Select a user'),
                        Forms\Components\Select::make('status')
                            ->options([
                                BookingAttendeeStatus::Accepted->value => BookingAttendeeStatus::Accepted->getLabel(),
                                BookingAttendeeStatus::Tentative->value => BookingAttendeeStatus::Tentative->getLabel(),
                                BookingAttendeeStatus::Declined->value => BookingAttendeeStatus::Declined->getLabel(),
                            ])
                            ->default(BookingAttendeeStatus::Accepted)
                            ->selectablePlaceholder(false)
                            ->helperText('If you do not know someone\'s availability you should invite them instead.')
                            ->required(),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (User $record, RelationManager $livewire) => $record->id === $livewire->getOwnerRecord()->lead_instructor_id),
                Tables\Actions\DetachAction::make()
                    ->hidden(fn (User $record, RelationManager $livewire) => $record->id === $livewire->getOwnerRecord()->lead_instructor_id)
                    ->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make()
                        ->label('Remove selected'),
                ]),
            ]);
    }
}
