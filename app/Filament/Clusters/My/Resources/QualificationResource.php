<?php

namespace App\Filament\Clusters\My\Resources;

use App\Filament\Clusters\Admin\Resources\QualificationResource as AdminQualificationResource;
use App\Filament\Clusters\My\Resources\QualificationResource\Pages;
use App\Models\Qualification;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class QualificationResource extends Resource
{
    protected static ?string $model = Qualification::class;

    protected static ?string $navigationIcon = 'heroicon-o-wallet';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereBelongsTo(Auth::user());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return AdminQualificationResource::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return AdminQualificationResource::table($table)
            ->recordAction('view')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQualifications::route('/'),
            'view' => Pages\ViewQualification::route('/{record}'),
        ];
    }
}
