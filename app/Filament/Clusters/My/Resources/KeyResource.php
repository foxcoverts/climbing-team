<?php

namespace App\Filament\Clusters\My\Resources;

use App\Filament\Clusters\My\Resources\KeyResource\Tables\Actions\TransferAction;
use App\Filament\Clusters\My\Resources\KeyResource\Pages;
use App\Models\Key;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use RalphJSmit\Filament\Activitylog;

class KeyResource extends Resource
{
    protected static ?string $model = Key::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $recordTitleAttribute = 'name';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('holder.name')
                    ->searchable(),
            ])
            ->defaultSort('name')
            ->actions([
                Activitylog\Tables\Actions\TimelineAction::make()
                    ->label('Log')
                    ->color('info'),
                TransferAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereBelongsTo(Auth::user(), 'holder');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKeys::route('/'),
        ];
    }
}
