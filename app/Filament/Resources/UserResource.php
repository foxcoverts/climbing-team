<?php

namespace App\Filament\Resources;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Enums\Section;
use App\Filament\Forms\Components as AppComponents;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Components\Placeholder::make('password')
                    ->content(fn (string $operation) => match ($operation) {
                        'create' => 'The user will be asked to set their own password.',
                        'edit' => 'Only the user can change their own password.',
                    })
                    ->hidden(fn (string $operation) => $operation === 'view'),
                Components\Section::make('Contact Details')
                    ->collapsed(fn (string $operation) => $operation === 'edit')
                    ->schema([
                        Components\TextInput::make('email')
                            ->email()
                            ->hint(fn (?User $record): ?string => match ($record?->hasVerifiedEmail()) {
                                true => 'Email Verified',
                                false => 'Email Unverified',
                                default => null,
                            })
                            ->hintIcon(fn (?User $record): ?string => match ($record?->hasVerifiedEmail()) {
                                true => 'heroicon-o-check-circle',
                                false => 'heroicon-o-x-circle',
                                default => null,
                            })
                            ->hintColor(fn (?User $record): ?array => match ($record?->hasVerifiedEmail()) {
                                true => Color::Lime,
                                false => Color::Pink,
                                default => null,
                            })
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        PhoneInput::make('phone')
                            ->defaultCountry('GB')
                            ->validateFor(['INTERNATIONAL', 'GB'])
                            ->visible(fn (string $operation) => $operation === 'edit')
                            ->nullable(),
                    ]),
                Components\Section::make('Emergency Contact')
                    ->description('The lead instructor for a booking will be able to access these details should the need arise. If no details are provided then there may be a delay in contacting someone.')
                    ->collapsed()
                    ->visible(fn (string $operation) => $operation === 'edit')
                    ->schema([
                        Components\TextInput::make('emergency_name')
                            ->maxLength(100)
                            ->requiredWith('emergency_phone')
                            ->nullable(),
                        PhoneInput::make('phone')
                            ->defaultCountry('GB')
                            ->validateFor(['INTERNATIONAL', 'GB'])
                            ->requiredWith('emergency_name')
                            ->nullable(),
                    ]),
                Components\Section::make('Settings')
                    ->collapsed(fn (string $operation) => $operation === 'edit')
                    ->schema([
                        Components\Select::make('section')
                            ->options(Section::class)
                            ->default(Section::Adult)
                            ->required(),
                        Components\Radio::make('role')
                            ->options(Role::class)
                            ->default(Role::Guest)
                            ->required(),
                        Components\CheckboxList::make('accreditations')
                            ->options(Accreditation::class)
                            ->bulkToggleable(),
                        AppComponents\TimezoneSelect::make('timezone')
                            ->searchable()
                            ->required()
                            ->defaultByBrowser(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with('keys', 'qualifications'))
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\ViewColumn::make('badges')
                    ->view('filament.resources.user-resource.columns.badges'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('accreditations')
                    ->options(Accreditation::class)
                    ->label('Accreditation'),
                Tables\Filters\SelectFilter::make('role')
                    ->options(Role::class),
                Tables\Filters\SelectFilter::make('section')
                    ->options(Section::class),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All')
                    ->trueLabel('Active users')
                    ->falseLabel('Inactive users')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNot('password', ''),
                        false: fn (Builder $query) => $query->where('password', ''),
                        blank: fn (Builder $query) => $query,
                    ),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
