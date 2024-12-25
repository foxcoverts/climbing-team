<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Filament\Clusters\Admin\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use RalphJSmit\Filament\Activitylog;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([Forms\Components\Section::make()->schema([
                Forms\Components\FileUpload::make('file_path')
                    ->disk('local')->directory('upload/document')
                    ->required()
                    ->columnSpanFull()
                    ->live()
                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                        if ($state instanceof TemporaryUploadedFile) {
                            $set('file_name', $state->getClientOriginalName());
                            if (! filled($get('title'))) {
                                $set('title', Str::of($state->getClientOriginalName())
                                    ->basename('.'.$state->getClientOriginalExtension())
                                    ->headline()
                                    ->limit(100, preserveWords: true)
                                );
                            }
                        }
                    }),
                Forms\Components\TextInput::make('file_name')
                    ->label('Filename')
                    ->helperText('This is the filename that a user will see when they download the document.')
                    ->regex('/[\w\-. ]+/')->extraInputAttributes(['pattern' => '[\w\-. ]+'])
                    ->required(),
                Forms\Components\TextInput::make('title')
                    ->autocapitalize('words')
                    ->required()
                    ->maxLength(100),
                Forms\Components\TextInput::make('category')
                    ->autocapitalize('words')
                    ->datalist(fn (): array => Document::distinct()
                        ->orderBy('category')
                        ->pluck('category')
                        ->all()
                    )
                    ->required()
                    ->maxLength(100),
                Forms\Components\MarkdownEditor::make('description')
                    ->columnSpanFull(),
            ])]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('title')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('category')
                    ->searchable()
                    ->options(fn (): array => Document::withTrashed()
                        ->distinct()
                        ->orderBy('category')
                        ->pluck('category', 'category')
                        ->all()
                    ),
            ])
            ->actions([
                Activitylog\Tables\Actions\TimelineAction::make()
                    ->label('Log')
                    ->color('info'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
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
                Activitylog\Infolists\Components\Timeline::make()
                    ->label('Activity Log')
                    ->columnSpanFull(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
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
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'view' => Pages\ViewDocument::route('/{record}'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
