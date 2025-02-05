<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Enums\BookingAttendeeStatus;
use App\Enums\BookingLeadInstructor;
use App\Models\User;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class GuestListRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $title = 'Guest List';

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('keys', 'scoutPermits'))
            ->recordTitleAttribute('name')
            ->groups([
                Tables\Grouping\Group::make('status')
                    ->titlePrefixedWithLabel(false)
                    ->collapsible(),
            ])
            ->defaultGroup('status')
            ->columns([
                BadgeableColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->state(fn (User $record) => match (true) {
                        $record->attendance->isLeadInstructor() => BookingLeadInstructor::LeadInstructor,
                        default => $record->status,
                    })
                    ->badge(),
                Tables\Columns\ViewColumn::make('badges')
                    ->view('filament.resources.user-resource.columns.badges'),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(BookingAttendeeStatus::class),
            ]);
    }
}
