<?php

namespace App\Filament\Clusters\Admin\Resources\UserResource\RelationManagers;

use App\Filament\Clusters\Admin\Resources\KitCheckResource;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class KitChecksRelationManager extends RelationManager
{
    protected static string $relationship = 'kitChecks';

    public function form(Form $form): Form
    {
        return KitCheckResource::form($form);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return KitCheckResource::infolist($infolist);
    }

    public function table(Table $table): Table
    {
        return KitCheckResource::table($table)
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->createAnother(false),
            ]);
    }
}
