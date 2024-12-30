<?php

namespace App\Filament\Clusters\My\Resources;

use App\Filament\Clusters\Admin\Resources\KitCheckResource as AdminKitCheckResource;
use App\Filament\Clusters\My\Resources\KitCheckResource\Pages;
use App\Models\KitCheck;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class KitCheckResource extends Resource
{
    protected static ?string $model = KitCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereBelongsTo(Auth::user());
    }

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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKitChecks::route('/'),
        ];
    }
}
