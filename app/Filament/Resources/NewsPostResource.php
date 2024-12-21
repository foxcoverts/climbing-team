<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NewsPostResource\Pages;
use App\Models\NewsPost;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use RalphJSmit\Filament\Activitylog;

class NewsPostResource extends Resource
{
    protected static ?string $model = NewsPost::class;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, ?NewsPost $record, ?string $old, ?string $state) {
                        $date = ($record?->created_at ?? Carbon::now())->format('Y-m-d ');

                        $dictionary = [
                            '@' => 'at',
                            '&' => 'and',
                        ];

                        if (filled($get('slug')) && $get('slug') !== Str::slug($date.$old, dictionary: $dictionary)) {
                            return;
                        }

                        $set('slug', Str::slug($date.$state, dictionary: $dictionary));
                    })
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->prefix('/')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules(['alpha_dash'])
                    ->maxLength(255),
                Forms\Components\Select::make('author_id')
                    ->relationship('author', 'name')
                    ->default(fn (Request $request) => $request->user()->id)
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\DateTimePicker::make('created_at')
                    ->disabled()
                    ->visibleOn('edit'),
                Forms\Components\MarkdownEditor::make('body')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('author.name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->actions([
                ActivityLog\Tables\Actions\TimelineAction::make()
                    ->label('Log')
                    ->color('info'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Split::make([
                    Infolists\Components\Section::make()
                        ->schema([
                            Infolists\Components\TextEntry::make('title')
                                ->hiddenLabel(true)
                                ->size('text-2xl')
                                ->weight(FontWeight::Medium),
                            Infolists\Components\TextEntry::make('body')
                                ->hiddenLabel(true)
                                ->markdown()->prose()
                                ->columnSpanFull(),
                        ])->grow(true),
                    Infolists\Components\Section::make()
                        ->schema([
                            Infolists\Components\TextEntry::make('slug')
                                ->prefix('/'),
                            Infolists\Components\TextEntry::make('created_at')
                                ->label('Created')
                                ->since()
                                ->dateTimeTooltip(),
                            Infolists\Components\TextEntry::make('author.name'),
                        ])->grow(false),
                ])->from('md')->columnSpanFull(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNewsPosts::route('/'),
            'create' => Pages\CreateNewsPost::route('/create'),
            'view' => Pages\ViewNewsPost::route('/{record}'),
            'edit' => Pages\EditNewsPost::route('/{record}/edit'),
        ];
    }
}
