<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Filament\Clusters\Admin\Resources\KeyResource\Pages;
use App\Filament\Clusters\My\Resources\KeyResource\Tables\Actions\TransferAction;
use App\Models\Key;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use RalphJSmit\Filament\Activitylog;

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
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('holder.name')
                    ->url(fn (Key $record) => UserResource::getUrl('view', ['record' => $record->holder_id, 'activeRelationManager' => 'keys']))
                    ->searchable(),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->actions([
                Activitylog\Tables\Actions\TimelineAction::make()
                    ->label('Log')
                    ->color('info'),
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
