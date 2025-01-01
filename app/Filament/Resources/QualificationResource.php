<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\Admin\Resources\QualificationResource as AdminQualificationResource;
use App\Filament\Resources\QualificationResource\Pages;
use App\Models\Qualification;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QualificationResource extends Resource
{
    protected static ?string $model = Qualification::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    protected static ?string $activeNavigationIcon = 'heroicon-s-wallet';

    public static function table(Table $table): Table
    {
        return AdminQualificationResource::table($table)
            ->recordAction('view')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return AdminQualificationResource::infolist($infolist);
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
            'index' => Pages\ListQualifications::route('/'),
            'view' => Pages\ViewQualification::route('/{record}'),
        ];
    }
}
