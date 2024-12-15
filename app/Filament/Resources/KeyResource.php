<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeyResource\Pages;
use App\Filament\Resources\KeyResource\Tables\Actions\TransferAction;
use App\Models\Key;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\Request;

class KeyResource extends Resource
{
    protected static ?string $model = Key::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('holder_id')
                            ->relationship('holder', 'name')
                            ->default(fn (Request $request) => $request->user()->id)
                            ->preload()
                            ->searchable()
                            ->required(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('holder.name')
                    ->url(fn (Key $record) => UserResource::getUrl('view', ['record' => $record->holder_id, 'activeRelationManager' => 'keys']))
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                TransferAction::make(),
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
            'index' => Pages\ListKeys::route('/'),
            'create' => Pages\CreateKey::route('/create'),
            'edit' => Pages\EditKey::route('/{record}/edit'),
        ];
    }
}
