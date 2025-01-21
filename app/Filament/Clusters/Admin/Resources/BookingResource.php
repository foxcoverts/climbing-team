<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Enums\BookingAttendeeStatus;
use App\Enums\BookingStatus;
use App\Filament\Clusters\Admin;
use App\Filament\Clusters\Admin\Resources\BookingResource\Pages;
use App\Filament\Forms\Components as AppComponents;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use RalphJSmit\Filament\Activitylog;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-s-calendar-days';

    protected static ?string $recordTitleAttribute = 'summary';

    protected static ?string $cluster = Admin::class;

    public static function getGloballySearchableAttributes(): array
    {
        return ['activity', 'group_name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Location' => $record->location,
            'Group' => $record->group_name,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make(fn (?Booking $record) => __(':Status Booking', [
                    'status' => $record?->status->getLabel() ?? __('app.booking.status.new'),
                ]))
                    ->icon(fn (?Booking $record) => ($record?->status ?? BookingStatus::Tentative)->getIcon())
                    ->iconColor(fn (?Booking $record) => ($record?->status ?? BookingStatus::Tentative)->getColor())
                    ->schema(fn (?Booking $record) => match ($record?->status) {
                        BookingStatus::Tentative => [
                            Forms\Components\Toggle::make('confirm')
                                ->label('Confirm booking')
                                ->helperText('Before you confirm this booking you should ensure that there are enough instructors attending and that you have chosen a Lead Instructor.')
                                ->default(false)
                                ->onIcon(BookingStatus::Confirmed->getIcon())
                                ->onColor(BookingStatus::Confirmed->getColor())
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, $state) {
                                    $set('status', ($state ? BookingStatus::Confirmed : BookingStatus::Tentative)->value);
                                }),
                        ],
                        BookingStatus::Confirmed => [
                            Forms\Components\Placeholder::make('confirmed')
                                ->hiddenLabel()
                                ->content('This booking has been confirmed, any changes you make will be sent to the attendees.'),
                        ],
                        BookingStatus::Cancelled => [
                            Forms\Components\Toggle::make('restore')
                                ->label('Restore booking')
                                ->helperText('If you restore this booking you will need to find instructors and confirm the booking again. All previous attendees will be re-invited to the booking. If you do not want to invite any of the previous attendees you should remove them from the guest list first.')
                                ->default(false)
                                ->live()
                                ->afterStateUpdated(function (Forms\Set $set, $state) {
                                    $set('status', ($state ? BookingStatus::Tentative : BookingStatus::Cancelled)->value);
                                }),
                        ],
                        default => [
                            Forms\Components\Placeholder::make('new')
                                ->hiddenLabel()
                                ->content('Before you confirm a booking you should ensure that there are enough instructors attending and that you have chosen a Lead Instructor. You can confirm this booking from the edit screen once you have created it.'),
                        ],
                    }),
                Forms\Components\Section::make('Booking Details')
                    ->schema([
                        Forms\Components\Split::make([
                            Forms\Components\Split::make([
                                Forms\Components\DatePicker::make('date')
                                    ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                                    ->default(fn () => Carbon::make('next Saturday'))
                                    ->required(),
                                Forms\Components\Split::make([
                                    Forms\Components\TimePicker::make('start_time')
                                        ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                                        ->default('10:00')
                                        ->formatStateUsing(fn (Forms\Get $get, $state) => Carbon::make($state)
                                            ->tz($get('timezone') ?? 'UTC')
                                            ->format('H:i')
                                        )
                                        ->required()
                                        ->seconds(false)
                                        ->live()
                                        ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $old, $state) {
                                            if (filled($state) && filled($old) && $get('end_time')) {
                                                $diff = Carbon::make($old)->diff($state);

                                                $set('end_time', Carbon::make($get('end_time'))->add($diff)->format('H:i'));
                                            }
                                        }),
                                    Forms\Components\TimePicker::make('end_time')
                                        ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                                        ->default('12:00')
                                        ->formatStateUsing(fn (Forms\Get $get, $state) => Carbon::make($state)
                                            ->tz($get('timezone') ?? 'UTC')
                                            ->format('H:i')
                                        )
                                        ->required()
                                        ->seconds(false),
                                ])->grow(false),
                            ]),
                            Forms\Components\Group::make([
                                AppComponents\TimezoneSelect::make('timezone')
                                    ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                                    ->searchable()
                                    ->required()
                                    ->selectablePlaceholder(false)
                                    ->defaultByBrowser()
                                    ->live(),
                            ])->grow(false),
                        ])->from('md'),

                        Forms\Components\TextInput::make('location')
                            ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                            ->default('Fox Coverts Campsite')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('activity')
                            ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                            ->default('Climbing')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('group_name')
                            ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                            ->required()
                            ->maxLength(255),
                        Forms\Components\MarkdownEditor::make('notes')
                            ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                            ->columnSpanFull(),
                    ]),
                Forms\Components\Section::make('Lead Instructor')->schema([
                    Forms\Components\Select::make('lead_instructor_id')
                        ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                        ->relationship(
                            'lead_instructor', 'name',
                            modifyQueryUsing: fn (Booking $record, Eloquent\Builder $query) => $query->whereIn('id',
                                $record->attendees()
                                    ->wherePivot('status', BookingAttendeeStatus::Accepted)
                                    ->whereHas('qualifications')
                                    ->select('id')
                            )->orWhere('id', $record->lead_instructor_id),
                        )
                        ->helperText('Someone missing? Only instructors who are going to this booking will appear here.')
                        ->selectablePlaceholder(fn (Forms\Get $get, $state) => blank($state) || $get('status') != BookingStatus::Confirmed->value)
                        ->disableOptionWhen(fn (Booking $record, string $value) => ! $record->attendees->contains($value))
                        ->required(fn (Forms\Get $get) => $get('status') == BookingStatus::Confirmed->value),

                    Forms\Components\MarkdownEditor::make('lead_instructor_notes')
                        ->disabled(fn (Forms\Get $get) => $get('status') == BookingStatus::Cancelled->value)
                        ->helperText('These notes will be visible to the Lead instructor. You can use these to share access arrangements, gate codes, etc.')
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Group::make([
                        Infolists\Components\Section::make(fn (Booking $record) => __(':Status Booking', [
                            'status' => $record->status->getLabel(),
                        ]))
                            ->icon(fn (Booking $record) => $record->status->getIcon())
                            ->iconColor(fn (Booking $record) => $record->status->getColor())
                            ->schema([
                                Infolists\Components\TextEntry::make('when')
                                    ->state(fn (Booking $record) => __(':date from :start_time to :end_time (:duration)', [
                                        'date' => $record->start_at->timezone($record->timezone)->toFormattedDayDateString(),
                                        'start_time' => $record->start_at->timezone($record->timezone)->format('H:i'),
                                        'end_time' => $record->end_at->timezone($record->timezone)->format('H:i'),
                                        'duration' => $record->start_at->diffAsCarbonInterval($record->end_at),
                                    ])),
                                Infolists\Components\TextEntry::make('location'),
                                Infolists\Components\TextEntry::make('activity'),
                                Infolists\Components\TextEntry::make('group_name')
                                    ->label('Group'),
                                Infolists\Components\TextEntry::make('notes')
                                    ->visible(fn (Booking $record) => filled($record->notes))
                                    ->markdown(),
                            ]),
                        Activitylog\Infolists\Components\Timeline::make()
                            ->label('Activity Log')
                            ->columnSpanFull(),
                    ]),
                    Infolists\Components\Group::make([
                        Infolists\Components\Section::make('Lead Instructor')
                            ->visible(fn (Booking $record) => filled($record->lead_instructor_id))
                            ->schema([
                                Infolists\Components\TextEntry::make('lead_instructor.name')
                                    ->hiddenLabel(),
                            ]),
                    ])->grow(false),
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('start_at', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->state(fn (Booking $record) => $record->start_at)
                    ->date()
                    ->timezone(fn (Booking $record) => $record->timezone),
                Tables\Columns\TextColumn::make('start_at')
                    ->time('H:i')
                    ->timezone(fn (Booking $record) => $record->timezone)
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_at')
                    ->time('H:i')
                    ->timezone(fn (Booking $record) => $record->timezone)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('activity')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('group_name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->emptyStateHeading(fn ($livewire) => __('No :tab bookings', [
                'tab' => $livewire->activeTab,
            ]))
            ->emptyStateDescription('Try picking another tab or changing the filters for more bookings.')
            ->filters([
                Tables\Filters\TernaryFilter::make('when')
                    ->placeholder('All bookings')
                    ->default(true)
                    ->trueLabel('Future bookings')
                    ->falseLabel('Past bookings')
                    ->queries(
                        true: fn (Eloquent\Builder $query) => $query->whereDate('start_at', '>=', Carbon::today()),
                        false: fn (Eloquent\Builder $query) => $query->whereDate('end_at', '<', Carbon::today()),
                        blank: fn (Eloquent\Builder $query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getEloquentQuery(): Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('attendees');
    }

    public static function getRelations(): array
    {
        return [
            'guest-list' => BookingResource\RelationManagers\GuestListRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
