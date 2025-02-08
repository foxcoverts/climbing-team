<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Enums\BookingAttendeeStatus;
use App\Enums\BookingLeadInstructor;
use App\Filament\Infolists\Components\GDPRSection;
use App\Models\GirlguidingQualification;
use App\Models\MountainTrainingQualification;
use App\Models\Qualification;
use App\Models\ScoutPermit;
use App\Models\User;
use Awcodes\FilamentBadgeableColumn\Components\BadgeableColumn;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Propaganistas\LaravelPhone\PhoneNumber;
use Ysfkaya\FilamentPhoneInput\Infolists\PhoneEntry;

class GuestListRelationManager extends RelationManager
{
    protected static string $relationship = 'attendees';

    protected static ?string $title = 'Guest List';

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Qualifications')
                    ->collapsed()
                    ->icon('heroicon-o-wallet')
                    ->visible(fn (User $record) => count($record->qualifications))
                    ->schema(function (User $record) {
                        $record->load('qualifications.detail');

                        return [
                            Infolists\Components\RepeatableEntry::make('qualifications')
                                ->hiddenLabel()
                                ->schema([
                                    Infolists\Components\TextEntry::make('detail_type')
                                        ->label('Type')
                                        ->formatStateUsing(fn (string $state): string => __("app.qualification.type.{$state}")),
                                    Infolists\Components\Group::make()
                                        ->relationship('detail')
                                        ->schema(fn (?Qualification $record): array => match ($record?->detail_type) {
                                            GirlguidingQualification::class => [
                                                Infolists\Components\TextEntry::make('summary')
                                                    ->label('Award'),
                                            ],
                                            MountainTrainingQualification::class => [
                                                Infolists\Components\TextEntry::make('award'),
                                            ],
                                            ScoutPermit::class => [
                                                Infolists\Components\TextEntry::make('summary')
                                                    ->label('Permit Type'),
                                                Infolists\Components\TextEntry::make('restrictions')
                                                    ->placeholder('None'),
                                            ],
                                            default => [
                                                Infolists\Components\TextEntry::make('summary'),
                                            ],
                                        }),
                                    Infolists\Components\TextEntry::make('expires_on')
                                        ->label('Expires')
                                        ->since()->dateTooltip()
                                        ->placeholder('Never')
                                        ->badge()->color(fn (Qualification $record): array => match (true) {
                                            $record->isExpired() => Color::Red,
                                            $record->expiresSoon() => Color::Amber,
                                            default => Color::Sky,
                                        }),
                                ]),
                        ];
                    }),
                GDPRSection::make('Contact Details')
                    ->collapsed()
                    ->icon('heroicon-o-phone')
                    ->hidden(fn (User $record) => blank($record->phone))
                    ->schema([
                        PhoneEntry::make('phone')
                            ->visible(fn ($state): bool => filled($state))
                            ->formatStateUsing(fn (PhoneNumber $state): string => $state->formatForCountry('GB')),
                    ]),
                Infolists\Components\Section::make('Emergency Contact')
                    ->collapsed()
                    ->icon('heroicon-o-question-mark-circle')
                    ->visible(fn (User $record) => blank($record->emergency_phone))
                    ->schema([
                        Infolists\Components\TextEntry::make('emergency_phone')
                            ->placeholder('This user has not provided an emergency contact. Please contact the Team Leader or Lead Volunteer if you need this information.'),
                    ]),
                GDPRSection::make('Emergency Contact')
                    ->collapsed()
                    ->icon('heroicon-o-exclamation-triangle')
                    ->hidden(fn (User $record) => blank($record->emergency_phone))
                    ->schema([
                        Infolists\Components\TextEntry::make('emergency_name')
                            ->visible(fn ($state): bool => filled($state)),
                        PhoneEntry::make('emergency_phone')
                            ->formatStateUsing(fn (PhoneNumber $state): string => $state->formatForCountry('GB'))
                            ->placeholder('This user has not provided an emergency contact. Please contact the Team Leader or Lead Volunteer if you need this information.'),
                    ]),
            ]);
    }

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
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }
}
