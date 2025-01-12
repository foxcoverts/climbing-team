<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsPostResource\Pages;
use App\Models\NewsPost;
use Filament\Facades\Filament;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class NewsPostResource extends Resource
{
    protected static ?string $model = NewsPost::class;

    protected static ?string $modelLabel = 'news';

    protected static ?string $slug = 'news';

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Posted' => $record->created_at->ago(),
        ];
    }

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
            ->schema(fn () => match (true) {
                Filament::auth()->check() => [
                    Infolists\Components\Split::make([
                        Infolists\Components\Section::make()
                            ->schema([
                                Infolists\Components\TextEntry::make('body')
                                    ->hiddenLabel()
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
                ],
                default => [ // guest
                    Infolists\Components\Section::make()
                        ->schema([
                            Infolists\Components\TextEntry::make('summary')
                                ->hiddenLabel()
                                ->markdown()->prose()
                                ->columnSpanFull(),
                        ])
                        ->grow(true)
                        ->footerActions([
                            Infolists\Components\Actions\Action::make('read-more')
                                ->label('Read More')
                                ->url(fn () => Filament::getCurrentPanel()->getLoginUrl()),
                        ])
                        ->footerActionsAlignment(Alignment::Center),
                    Infolists\Components\TextEntry::make('please-login')
                        ->state('Please login to view the full post.')
                        ->url(fn () => Filament::getCurrentPanel()->getLoginUrl())
                        ->hiddenLabel()
                        ->alignCenter()
                        ->columnSpanFull(),
                ]
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsPosts::route('/'),
            'view' => Pages\ViewNewsPost::route('/{record}'),
        ];
    }
}
