<?php

namespace App\Filament\Resources\QualificationResource\Pages;

use App\Enums\GirlguidingScheme;
use App\Enums\MountainTrainingAward;
use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;
use App\Filament\Resources\QualificationResource;
use App\Models\GirlguidingQualification;
use App\Models\MountainTrainingQualification;
use App\Models\Qualification;
use App\Models\ScoutPermit;
use Filament\Forms;
use Filament\Forms\Components\Wizard\Step;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;

class CreateQualification extends CreateRecord
{
    use CreateRecord\Concerns\HasWizard;

    protected static string $resource = QualificationResource::class;

    protected function getSteps(): array
    {
        return [
            Step::make('User')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                ]),
            Step::make('Qualification')
                ->schema([
                    Forms\Components\Select::make('detail_type')
                        ->label('Qualification type')
                        ->options([
                            GirlguidingQualification::class => __('app.qualification.type.App\Models\GirlguidingQualification'),
                            MountainTrainingQualification::class => __('app.qualification.type.App\Models\MountainTrainingQualification'),
                            ScoutPermit::class => __('app.qualification.type.App\Models\ScoutPermit'),
                        ])
                        ->required()
                        ->selectablePlaceholder(fn (?string $state) => empty($state)),
                ]),
            Step::make('Details')
                ->schema([
                    Forms\Components\Group::make()
                        ->visible(fn (Forms\Get $get) => $get('detail_type') === GirlguidingQualification::class)
                        ->schema([
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
                            Forms\Components\DatePicker::make('expires_on')
                                ->required(),
                        ]),
                    Forms\Components\Group::make()
                        ->visible(fn (Forms\Get $get) => $get('detail_type') === MountainTrainingQualification::class)
                        ->schema([
                            Forms\Components\Select::make('award')
                                ->options(MountainTrainingAward::class)
                                ->default(MountainTrainingAward::ClimbingWallInstructor)
                                ->required()
                                ->selectablePlaceholder(false),
                        ]),
                    Forms\Components\Group::make()
                        ->visible(fn (Forms\Get $get) => $get('detail_type') === ScoutPermit::class)
                        ->schema([
                            Forms\Components\Select::make('activity')
                                ->options(ScoutPermitActivity::class)
                                ->default(ScoutPermitActivity::ClimbingAndAbseiling)
                                ->selectablePlaceholder(false)
                                ->required(),
                            Forms\Components\Select::make('category')
                                ->options(ScoutPermitCategory::class)
                                ->default(ScoutPermitCategory::ArtificialTopRope)
                                ->selectablePlaceholder(false)
                                ->required(),
                            Forms\Components\Select::make('permit_type')
                                ->options(ScoutPermitType::class)
                                ->default(ScoutPermitType::Leadership)
                                ->selectablePlaceholder(false)
                                ->required(),
                            Forms\Components\Textarea::make('restrictions')
                                ->placeholder('None')
                                ->autosize()
                                ->nullable(),
                            Forms\Components\DatePicker::make('expires_on')
                                ->required(),
                        ]),
                ]),
        ];
    }

    protected function handleRecordCreation(array $data): Qualification
    {
        $record = new Qualification;
        $record->fill(Arr::only($data, 'expires_on'));

        if (Arr::has($data, 'user_id')) {
            $record->user()->associate($data['user_id']);
        }

        if (Arr::has($data, 'detail_type')) {
            $detail = new $data['detail_type'];
            $detail->fill(Arr::except($data, ['detail_type', 'expires_on', 'user_id']));
            $detail->save();

            $record->detail()->associate($detail);
        }

        $record->save();

        return $record;
    }
}
