<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsPostResource\Pages;
use App\Models\NewsPost;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;

class NewsPostResource extends Resource
{
    protected static ?string $model = NewsPost::class;

    protected static ?string $modelLabel = 'news';

    protected static ?string $slug = 'news';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordTitleAttribute = 'title';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Stack::make([
                    Tables\Columns\TextColumn::make('title')
                        ->size('text-xl')
                        ->weight(FontWeight::Medium),
                    Tables\Columns\TextColumn::make('summary')
                        ->html(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make()
                        ->schema([
                            Infolists\Components\TextEntry::make('body')
                                ->hiddenLabel(true)
                                ->markdown()->prose()
                                ->columnSpanFull(),
                        ])->grow(true),
                    Infolists\Components\Section::make()
                        ->schema([
                            Infolists\Components\TextEntry::make('author.name'),
                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Posted')
                                ->since()
                                ->dateTimeTooltip(),
                        ])->grow(false),
                ])->from('md')->columnSpanFull(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsPosts::route('/'),
            'view' => Pages\ViewNewsPost::route('/{record}'),
        ];
    }
}
