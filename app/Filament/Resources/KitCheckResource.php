<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Admin\Resources\KitCheckResource as AdminKitCheckResource;
use App\Filament\Resources\KitCheckResource\Pages;
use App\Models\KitCheck;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KitCheckResource extends Resource
{
    protected static ?string $model = KitCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    protected static ?string $activeNavigationIcon = 'heroicon-s-document-check';

    public static function table(Table $table): Table
    {
        return AdminKitCheckResource::table($table)
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return AdminKitCheckResource::infolist($infolist);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereBelongsTo(Auth::user());
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
            'index' => Pages\ListKitChecks::route('/'),
        ];
    }
}
