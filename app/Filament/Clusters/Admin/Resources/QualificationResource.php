<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Enums\GirlguidingScheme;
use App\Enums\MountainTrainingAward;
use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;
use App\Filament\Clusters\Admin\Resources\QualificationResource\Pages;
use App\Filament\Clusters\My\Resources\QualificationResource as MyQualificationResource;
use App\Models\GirlguidingQualification;
use App\Models\MountainTrainingQualification;
use App\Models\Qualification;
use App\Models\ScoutPermit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent;
use Illuminate\Database\Query;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Unique;

class QualificationResource extends Resource
{
    protected static ?string $model = Qualification::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('user')
                            ->content(fn (Qualification $record) => $record->user->name)
                            ->hiddenOn(UserResource\RelationManagers\QualificationsRelationManager::class),
                        Forms\Components\Placeholder::make('detail_type')
                            ->label('Qualification type')
                            ->content(fn (Qualification $record) => match ($record->detail_type) {
                                GirlguidingQualification::class => __('app.qualification.type.App\Models\GirlguidingQualification'),
                                MountainTrainingQualification::class => __('app.qualification.type.App\Models\MountainTrainingQualification'),
                                ScoutPermit::class => __('app.qualification.type.App\Models\ScoutPermit'),
                                default => '',
                            }),
                        Forms\Components\Group::make()
                            ->relationship('detail')
                            ->schema(fn (Forms\Get $get, Qualification $qualification) => match ($get('detail_type')) {
                                GirlguidingQualification::class => [
                                    Forms\Components\Select::make('scheme')
                                        ->options(GirlguidingScheme::class)
                                        ->default(GirlguidingScheme::Climbing)
                                        ->required()
                                        ->selectablePlaceholder(fn (?GirlguidingScheme $state) => empty($state)),
                                    Forms\Components\TextInput::make('level')
                                        ->integer()
                                        ->minValue(1)
                                        ->maxValue(2)
                                        ->default(1)
                                        ->required(),
                                ],
                                MountainTrainingQualification::class => [
                                    Forms\Components\Select::make('award')
                                        ->options(MountainTrainingAward::class)
                                        ->default(MountainTrainingAward::ClimbingWallInstructor)
                                        ->disableOptionWhen(fn (MountainTrainingQualification $record, $value): bool => Qualification::query()
                                            ->where('user_id', $qualification->user_id)
                                            ->whereHasMorph('detail', MountainTrainingQualification::class, fn (Eloquent\Builder $query) => $query
                                                ->whereNot('id', $record->id)
                                                ->where('award', $value)
                                            )
                                            ->exists()
                                        )
                                        ->unique(ignoreRecord: true, table: MountainTrainingQualification::class, modifyRuleUsing: fn (Unique $rule) => $rule->where(fn (Query\Builder $query) => $query
                                            ->whereExists(fn (Query\Builder $where) => $where
                                                ->select(DB::raw(1))
                                                ->from('qualifications')
                                                ->where('qualifications.detail_type', MountainTrainingQualification::class)
                                                ->whereColumn('mountain_training_qualifications.id', 'qualifications.detail_id')
                                                ->where('qualifications.user_id', $qualification->user_id)
                                            )
                                        ))
                                        ->required()
                                        ->selectablePlaceholder(fn (?MountainTrainingAward $state) => empty($state)),
                                ],
                                ScoutPermit::class => [
                                    Forms\Components\Select::make('activity')
                                        ->options(ScoutPermitActivity::class)
                                        ->default(ScoutPermitActivity::ClimbingAndAbseiling)
                                        ->required()
                                        ->selectablePlaceholder(fn (?ScoutPermitActivity $state) => empty($state)),
                                    Forms\Components\Select::make('category')
                                        ->options(ScoutPermitCategory::class)
                                        ->default(ScoutPermitCategory::ArtificialTopRope)
                                        ->required()
                                        ->selectablePlaceholder(fn (?ScoutPermitCategory $state) => empty($state)),
                                    Forms\Components\Select::make('permit_type')
                                        ->options(ScoutPermitType::class)
                                        ->default(ScoutPermitType::Leadership)
                                        ->required()
                                        ->selectablePlaceholder(fn (?ScoutPermitType $state) => empty($state)),
                                    Forms\Components\Textarea::make('restrictions')
                                        ->placeholder('None')
                                        ->autosize()
                                        ->nullable(),
                                ],
                                default => [],
                            }),
                        Forms\Components\DatePicker::make('expires_on')
                            ->visible(fn (Forms\Get $get) => filled($get('detail_type')))
                            ->hidden(fn (Forms\Get $get) => $get('detail_type') == MountainTrainingQualification::class)
                            ->required(),
                    ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make()->schema([
                Infolists\Components\TextEntry::make('user.name')
                    ->url(fn (Qualification $record) => UserResource::getUrl('view', ['record' => $record->user_id, 'activeRelationManager' => 'qualifications']))
                    ->hidden(fn ($component): bool => in_array($component->getLivewire()::class, [
                        UserResource\RelationManagers\QualificationsRelationManager::class,
                        MyQualificationResource\Pages\ViewQualification::class,
                    ])),
                Infolists\Components\TextEntry::make('detail_type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => __("app.qualification.type.{$state}")),
                Infolists\Components\Group::make()
                    ->relationship('detail')
                    ->schema(fn (?Qualification $record): array => match ($record?->detail_type) {
                        GirlguidingQualification::class => [
                            Infolists\Components\TextEntry::make('scheme'),
                            Infolists\Components\TextEntry::make('level'),
                        ],
                        MountainTrainingQualification::class => [
                            Infolists\Components\TextEntry::make('award'),
                        ],
                        ScoutPermit::class => [
                            Infolists\Components\TextEntry::make('activity'),
                            Infolists\Components\TextEntry::make('category'),
                            Infolists\Components\TextEntry::make('permit_type'),
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
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->url(fn (Qualification $record) => UserResource::getUrl('view', ['record' => $record->user_id, 'activeRelationManager' => 'qualifications']))
                    ->searchable()
                    ->hiddenOn([
                        UserResource\RelationManagers\QualificationsRelationManager::class,
                        MyQualificationResource\Pages\ListQualifications::class,
                    ]),
                Tables\Columns\TextColumn::make('detail_type')
                    ->label('Type')
                    ->state(fn (Qualification $record) => __('app.qualification.type.'.$record->detail_type)),
                Tables\Columns\TextColumn::make('detail.summary')
                    ->label('Summary'),
                Tables\Columns\TextColumn::make('expires_on')
                    ->label('Expires')
                    ->since()->dateTooltip()
                    ->placeholder('Never')
                    ->badge()->color(fn (Qualification $record): array => match (true) {
                        $record->isExpired() => Color::Red,
                        $record->expiresSoon() => Color::Amber,
                        default => Color::Sky,
                    })
                    ->sortable(query: fn (Eloquent\Builder $query, string $direction): Eloquent\Builder => match ($direction) {
                        'asc' => $query->orderByRaw('-expires_on DESC'),
                        'desc' => $query->orderByRaw('-expires_on ASC'),
                        default => $query,
                    }),
            ])
            ->defaultSort('expires_on')
            ->filters([
                Tables\Filters\SelectFilter::make('detail_type')
                    ->label('Type')
                    ->multiple()
                    ->options([
                        GirlguidingQualification::class => __('app.qualification.type.App\Models\GirlguidingQualification'),
                        MountainTrainingQualification::class => __('app.qualification.type.App\Models\MountainTrainingQualification'),
                        ScoutPermit::class => __('app.qualification.type.App\Models\ScoutPermit'),
                    ]),
                Tables\Filters\SelectFilter::make('expires')
                    ->label('Expires')
                    ->default('future')
                    ->options([
                        'future' => 'Not expired',
                        'someday' => 'Someday',
                        'never' => 'Never',
                        'soon' => 'Soon',
                        'expired' => 'Expired',
                    ])
                    ->query(fn (Eloquent\Builder $query, array $data): Eloquent\Builder => match ($data['value']) {
                        'future' => $query->where(function (Eloquent\Builder $query): void {
                            $query->whereNull('expires_on')
                                ->orWhereDate('expires_on', '>=', Carbon::now());
                        }),
                        'someday' => $query->whereDate('expires_on', '>=', Carbon::now()),
                        'never' => $query->whereNull('expires_on'),
                        'soon' => $query->whereDate('expires_on', '>=', Carbon::now())->whereDate('expires_on', '<', Carbon::now()->addMonths(3)),
                        'expired' => $query->whereDate('expires_on', '<', Carbon::now()),
                        default => $query
                    })
                    ->indicateUsing(fn (array $data): null|string|Indicator => match ($data['value']) {
                        'future' => Indicator::make('Not expired')->color(Color::Sky),
                        'someday' => Indicator::make('Expires: Someday')->color(Color::Sky),
                        'never' => Indicator::make('Expires: Never'),
                        'soon' => Indicator::make('Expires: Soon')->color(Color::Amber),
                        'expired' => Indicator::make('Expired')->color(Color::Red),
                        default => null,

                    }),
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

    public static function getWidgets(): array
    {
        return [
            QualificationResource\Widgets\QualificationTypeChart::class,
        ];
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
            'index' => Pages\ListQualifications::route('/'),
            'create' => Pages\CreateQualification::route('/create'),
            'view' => Pages\ViewQualification::route('/{record}'),
            'edit' => Pages\EditQualification::route('/{record}/edit'),
        ];
    }
}
