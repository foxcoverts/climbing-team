<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn\IconColumnSize;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'title';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Category' => $record->category,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['title', 'category'];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\IconColumn::make('file_name')
                        ->icon(fn (string $state) => 'filetype-v-'.pathinfo($state, PATHINFO_EXTENSION))
                        ->size(IconColumnSize::TwoExtraLarge)
                        ->grow(false),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('title')
                            ->size(TextColumnSize::Large)
                            ->weight(FontWeight::Medium),
                        Tables\Columns\TextColumn::make('description')
                            ->markdown(),
                    ]),
                ]),
            ])
            ->defaultSort('title')
            ->actions([
                Tables\Actions\Action::make('download')
                    ->iconButton()
                    ->color('info')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->action(fn (Document $record) => Storage::response($record->file_path, $record->file_name)),
            ])
            ->recordAction('download')
            ->recordUrl(null);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()->schema([
                    Infolists\Components\TextEntry::make('title'),
                    Infolists\Components\TextEntry::make('category'),
                    Infolists\Components\TextEntry::make('description')
                        ->markdown()
                        ->columnSpanFull(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'view' => Pages\ViewDocument::route('/{record}'),
        ];
    }
}
