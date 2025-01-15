<?php

namespace App\Filament\Clusters\Admin\Resources;

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
                Forms\Components\Section::make()->schema([
                    Forms\Components\Split::make([
                        Forms\Components\Split::make([
                            Forms\Components\DatePicker::make('date')
                                ->default(fn () => Carbon::make('next Saturday'))
                                ->required(),
                            Forms\Components\Split::make([
                                Forms\Components\TimePicker::make('start_time')
                                    ->default('10:00')
                                    ->formatStateUsing(fn (Forms\Get $get, $state) => Carbon::make($state)
                                        ->tz($get('timezone'))
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
                                    ->default('12:00')
                                    ->formatStateUsing(fn (Forms\Get $get, $state) => Carbon::make($state)
                                        ->tz($get('timezone'))
                                        ->format('H:i')
                                    )
                                    ->required()
                                    ->seconds(false),
                            ])->grow(false),
                        ]),
                        Forms\Components\Group::make([
                            AppComponents\TimezoneSelect::make('timezone')
                                ->searchable()
                                ->required()
                                ->selectablePlaceholder(false)
                                ->defaultByBrowser()
                                ->live(),
                        ])->grow(false),
                    ])->from('md'),

                    Forms\Components\TextInput::make('location')
                        ->default('Fox Coverts Campsite')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('activity')
                        ->default('Climbing')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('group_name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\MarkdownEditor::make('notes')
                        ->columnSpanFull(),

                    // Forms\Components\Select::make('status')
                    //     ->options(BookingStatus::class)
                    //     ->default(BookingStatus::Tentative)
                    //     ->selectablePlaceholder(false)
                    //     ->required(),
                    // Forms\Components\Select::make('lead_instructor_id')
                    //     ->relationship('lead_instructor', 'name')
                    //     ->helperText('Someone missing? Only instructors who are going to this booking will appear here.')
                    //     ->requiredIf('status', BookingStatus::Confirmed->value),
                    // Forms\Components\Textarea::make('lead_instructor_notes')
                    //     ->helperText('The Lead instructor notes will only be visible to the Lead instructor. You can use these to share access arrangements, gate codes, etc.')
                    //     ->autosize()
                    //     ->columnSpanFull(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()
                    ->schema([
                        Infolists\Components\TextEntry::make('status')
                            ->hiddenLabel()
                            ->badge(),
                        Infolists\Components\TextEntry::make('when')
                            ->state(fn (Booking $record) => __(':date from :start_time to :end_time (:duration)', [
                                'date' => $record->start_at->timezone($record->timezone)->toFormattedDayDateString(),
                                'start_time' => $record->start_at->timezone($record->timezone)->format('H:i'),
                                'end_time' => $record->end_at->timezone($record->timezone)->format('H:i'),
                                'duration' => $record->start_at->diffAsCarbonInterval($record->end_at),
                            ])),
                        Infolists\Components\TextEntry::make('location'),
                        Infolists\Components\TextEntry::make('activity'),
                        Infolists\Components\TextEntry::make('group_name'),
                        Infolists\Components\TextEntry::make('notes')
                            ->markdown(),
                    ]),
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
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Eloquent\Builder
    {
        return parent::getEloquentQuery()->with('attendees');
    }

    public static function getRelations(): array
    {
        return [
            //
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
