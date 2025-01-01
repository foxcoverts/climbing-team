<?php

namespace App\Filament\Clusters\Admin\Resources;

use App\Enums\Accreditation;
use App\Enums\Role;
use App\Filament\Clusters\Admin\Resources\KitCheckResource\Pages;
use App\Filament\Resources\KitCheckResource as MyKitCheckResource;
use App\Models\KitCheck;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class KitCheckResource extends Resource
{
    protected static ?string $model = KitCheck::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('checked_on')
                    ->default(Carbon::now())
                    ->maxDate(Carbon::now())
                    ->required(),
                Forms\Components\Select::make('checked_by_id')
                    ->relationship(
                        name: 'checked_by', titleAttribute: 'name',
                        modifyQueryUsing: fn (Builder $query) => $query
                            ->whereRaw('FIND_IN_SET(?, accreditations)', [Accreditation::KitChecker->value])
                            ->orWhere('role', Role::TeamLeader)
                    )
                    ->default(fn (Request $request) => $request->user()->id)
                    ->preload()
                    ->searchable()
                    ->required(),
                Forms\Components\Textarea::make('comment')
                    ->columnSpanFull(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->hiddenOn(UserResource\RelationManagers\KitChecksRelationManager::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->url(fn (KitCheck $record): ?string => UserResource::canView($record->user)
                        ? UserResource::getUrl('view', ['record' => $record->user, 'activeRelationManager' => 'kitChecks'])
                        : null
                    )
                    ->searchable()
                    ->hiddenOn([
                        UserResource\RelationManagers\KitChecksRelationManager::class,
                        MyKitCheckResource\Pages\ListKitChecks::class,
                    ]),
                Tables\Columns\TextColumn::make('checked_on')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('checked_by.name')
                    ->url(fn (KitCheck $record): ?string => UserResource::canView($record->checked_by)
                        ? UserResource::getUrl('view', ['record' => $record->checked_by, 'activeRelationManager' => 'kitChecks'])
                        : null
                    )
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->state(fn (KitCheck $record): string => match ($record->isExpired()) {
                        true => 'Expired',
                        false => 'Good',
                    })
                    ->badge()
                    ->color(fn (KitCheck $record) => match ($record->isExpired()) {
                        true => 'danger',
                        false => Color::Lime,
                    }),
            ])
            ->defaultSort('checked_on', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'good' => 'Good',
                        'expired' => 'Expired',
                    ])
                    ->default('good')
                    ->query(fn (Builder $query, array $data): Builder => match ($data['value']) {
                        'good' => $query->whereDate('checked_on', '>=', Carbon::now()->subYear(1)),
                        'expired' => $query->whereDate('checked_on', '<', Carbon::now()->subYear(1)),
                        default => $query,
                    }),
            ])
            ->actions([
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
        return $infolist->schema([
            Infolists\Components\TextEntry::make('checked_on')
                ->date(),
            Infolists\Components\TextEntry::make('checked_by.name')
                ->url(fn (KitCheck $record): ?string => UserResource::canView($record->checked_by)
                    ? UserResource::getUrl('view', ['record' => $record->checked_by, 'activeRelationManager' => 'kitChecks'])
                    : null
                ),
            Infolists\Components\TextEntry::make('comment')
                ->columnSpanFull(),
            Infolists\Components\TextEntry::make('user.name')
                ->url(fn (KitCheck $record): ?string => UserResource::canView($record->user)
                    ? UserResource::getUrl('view', ['record' => $record->user, 'activeRelationManager' => 'kitChecks'])
                    : null
                )
                ->hidden(fn ($component): bool => in_array($component->getLivewire()::class, [
                    UserResource\RelationManagers\KitChecksRelationManager::class,
                    MyKitCheckResource\Pages\ListKitChecks::class,
                ])),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKitChecks::route('/'),
            'create' => Pages\CreateKitCheck::route('/create'),
            'edit' => Pages\EditKitCheck::route('/{record}/edit'),
        ];
    }
}
