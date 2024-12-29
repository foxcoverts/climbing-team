<?php

namespace App\Filament\Clusters\Admin\Resources\UserResource\RelationManagers;

use App\Filament\Clusters\My\Resources\KeyResource\Tables\Actions\TransferAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class KeysRelationManager extends RelationManager
{
    protected static string $relationship = 'keys';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->inverseRelationship('holder')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->label('Give key')
                    ->color('primary')
                    ->modalHeading('Give Key')
                    ->modalSubmitActionLabel('Give')
                    ->associateAnother(false),
            ])
            ->actions([
                TransferAction::make(),
            ]);
    }
}
