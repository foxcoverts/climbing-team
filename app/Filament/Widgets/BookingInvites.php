<?php

namespace App\Filament\Widgets;

use App\Enums\BookingAttendeeStatus;
use App\Filament\Tables\Actions\RespondAction;
use App\Models\Booking;
use Filament\Facades\Filament;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BookingInvites extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->heading('Invitations')
            ->query(
                Booking::future()
                    ->notCancelled()
                    ->attendeeStatus(Filament::auth()->user(), [
                        BookingAttendeeStatus::NeedsAction,
                    ])
            )
            ->defaultSort('start_at', 'asc')
            ->defaultGroup('start_at')
            ->paginated(false)
            ->groups([
                Tables\Grouping\Group::make('start_at')
                    ->titlePrefixedWithLabel(false)
                    ->date(),
            ])
            ->groupingSettingsHidden()
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('start_at')
                        ->verticalAlignment(VerticalAlignment::Start)
                        ->label('Start')
                        ->time('H:i')
                        ->timezone(fn (Booking $record) => $record->timezone)
                        ->grow(false),
                    Tables\Columns\TextColumn::make('-')
                        ->state('-')
                        ->grow(false),
                    Tables\Columns\TextColumn::make('end_at')
                        ->verticalAlignment(VerticalAlignment::Start)
                        ->label('End')
                        ->time('H:i')
                        ->timezone(fn (Booking $record) => $record->timezone)
                        ->grow(false),
                    Tables\Columns\TextColumn::make('description')
                        ->verticalAlignment(VerticalAlignment::Start)
                        ->state(fn (Booking $booking) => __(':activity for :group', [
                            'activity' => $booking->activity,
                            'group' => $booking->group_name,
                        ]))
                        ->extraAttributes([
                            'class' => 'text-wrap',
                        ]),
                ]),
            ])
            ->emptyStateHeading('No invitations')
            ->emptyStateIcon('heroicon-o-inbox')
            ->recordUrl(null)
            ->actions([RespondAction::make()])
            ->recordAction(RespondAction::class);
    }
}
