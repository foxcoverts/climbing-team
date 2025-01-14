<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KeyResource\Pages;
use App\Filament\Resources\KeyResource\Tables\Actions\TransferAction;
use App\Models\Key;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent;
use Illuminate\Support\Facades\Auth;
use RalphJSmit\Filament\Activitylog;

class KeyResource extends Resource
{
    protected static ?string $model = Key::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $activeNavigationIcon = 'heroicon-s-key';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchEloquentQuery(): Eloquent\Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['holder']);
    }

    public static function getGlobalSearchResultDetails(Eloquent\Model $record): array
    {
        return [
            'Holder' => $record->holder->name,
        ];
    }

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

    public static function getEloquentQuery(): Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->whereBelongsTo(Auth::user(), 'holder');
    }

    public static function canViewAny(): bool
    {
        return static::can('viewOwn');
    }

    public static function authorizeViewAny(): void
    {
        static::authorize('viewOwn');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKeys::route('/'),
        ];
    }
}
