<?php

namespace App\Filament\Resources;

use App\Enums\GirlguidingScheme;
use App\Enums\MountainTrainingAward;
use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;
use App\Filament\Resources\QualificationResource\Pages;
use App\Models\GirlguidingQualification;
use App\Models\MountainTrainingQualification;
use App\Models\Qualification;
use App\Models\ScoutPermit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class QualificationResource extends Resource
{
    protected static ?string $model = Qualification::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('user')
                            ->content(fn (Qualification $record) => $record->user->name),
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
                            ->schema(fn (Forms\Get $get) => match ($get('detail_type')) {
                                GirlguidingQualification::class => [
                                    Forms\Components\Select::make('scheme')
                                        ->options(GirlguidingScheme::class)
                                        ->default(GirlguidingScheme::Climbing)
                                        ->required()
                                        ->selectablePlaceholder(false),
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
                                        ->required()
                                        ->selectablePlaceholder(false),
                                ],
                                ScoutPermit::class => [
                                    Forms\Components\Select::make('activity')
                                        ->options(ScoutPermitActivity::class)
                                        ->default(ScoutPermitActivity::ClimbingAndAbseiling)
                                        ->required()
                                        ->selectablePlaceholder(false),
                                    Forms\Components\Select::make('category')
                                        ->options(ScoutPermitCategory::class)
                                        ->default(ScoutPermitCategory::ArtificialTopRope)
                                        ->required()
                                        ->selectablePlaceholder(false),
                                    Forms\Components\Select::make('permit_type')
                                        ->options(ScoutPermitType::class)
                                        ->default(ScoutPermitType::Leadership)
                                        ->required()
                                        ->selectablePlaceholder(false),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('detail_type')
                    ->label('Qualification Type')
                    ->state(fn (Qualification $record) => __('app.qualification.type.'.$record->detail_type)),
                Tables\Columns\TextColumn::make('expires_on')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('detail_type')
                    ->label('Qualification Type')
                    ->options([
                        GirlguidingQualification::class => __('app.qualification.type.App\Models\GirlguidingQualification'),
                        MountainTrainingQualification::class => __('app.qualification.type.App\Models\MountainTrainingQualification'),
                        ScoutPermit::class => __('app.qualification.type.App\Models\ScoutPermit'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'edit' => Pages\EditQualification::route('/{record}/edit'),
        ];
    }
}
