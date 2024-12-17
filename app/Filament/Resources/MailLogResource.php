<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MailLogResource\Pages;
use App\Models\Calendar\Attendee;
use App\Models\Calendar\Event;
use App\Models\MailLog;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontFamily;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\HtmlString;

class MailLogResource extends Resource
{
    protected static ?string $model = MailLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::unread()->count();
        if ($count === 0) {
            return null;
        }

        return $count;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'info';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of unread mail logs';
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema(fn (MailLog $record): array => match (self::mailLogKind($record)) {
            'calendar' => [
                Infolists\Components\Section::make('Calendar')
                    ->icon(fn (MailLog $record): string => $record->done
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-x-circle'
                    )
                    ->iconColor(fn (MailLog $record) => $record->done
                        ? Color::Green
                        : 'danger'
                    )
                    ->relationship('calendar')
                    ->schema([
                        Infolists\Components\TextEntry::make('method'),
                        Infolists\Components\RepeatableEntry::make('events')
                            ->hiddenLabel(true)
                            ->contained(false)
                            ->schema([
                                Infolists\Components\TextEntry::make('sent_at')
                                    ->dateTime(),
                                Infolists\Components\TextEntry::make('booking')
                                    ->state(fn (Event $record) => $record->booking?->summary ?? $record->uid)
                                    ->icon(fn (Event $record): ?string => $record->booking
                                        ? 'heroicon-o-calendar-days'
                                        : null
                                    ),
                                Infolists\Components\RepeatableEntry::make('attendees')
                                    ->hiddenLabel(true)
                                    ->contained(false)
                                    ->schema([
                                        Infolists\Components\TextEntry::make('attendee')
                                            ->state(fn (Attendee $record) => $record->user?->name ?? $record->email)
                                            ->icon(fn (Attendee $record): ?string => $record->user
                                                ? 'heroicon-o-user'
                                                : null
                                            )
                                            ->url(fn (Attendee $record) => $record->user
                                                ? UserResource::getUrl('view', ['record' => $record->user])
                                                : null
                                            ),
                                        Infolists\Components\TextEntry::make('status'),
                                        Infolists\Components\TextEntry::make('comment')
                                            ->visible(fn ($state): bool => filled($state)),
                                    ]),
                            ]),
                    ]),
                Infolists\Components\Section::make('Raw')
                    ->collapsible()->collapsed()
                    ->schema([
                        Infolists\Components\TextEntry::make('calendar.raw')
                            ->formatStateUsing(fn (string $state) => new HtmlString('<pre class="whitespace-pre">'.$state.'</pre>'))
                            ->fontFamily(FontFamily::Mono)
                            ->extraEntryWrapperAttributes(['class' => 'overflow-x-auto'])
                            ->hiddenLabel(),
                    ]),
            ],
            default => [
                Infolists\Components\Section::make('E-mail')
                    ->icon(fn (MailLog $record): string => $record->done
                        ? 'heroicon-o-check-circle'
                        : 'heroicon-o-x-circle'
                    )
                    ->iconColor(fn (MailLog $record) => $record->done
                        ? Color::Green
                        : 'danger'
                    )
                    ->schema([
                        Infolists\Components\TextEntry::make('sent_at')
                            ->label('Sent')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('to')
                            ->state(fn (MailLog $record) => $record->toBooking?->summary ?? $record->toEmail)
                            ->icon(fn (MailLog $record) => $record->toBooking
                                ? 'heroicon-o-calendar-days'
                                : null
                            ),
                        Infolists\Components\TextEntry::make('from')
                            ->state(fn (MailLog $record) => $record->fromUser?->name ?? $record->fromEmail)
                            ->icon(fn (MailLog $record) => $record->fromUser
                                ? 'heroicon-o-user'
                                : null
                            )
                            ->url(fn (MailLog $record) => $record->fromUser
                                ? UserResource::getUrl('view', ['record' => $record->fromUser])
                                : null
                            ),
                        Infolists\Components\TextEntry::make('subject')
                            ->columnSpanFull(),
                        Infolists\Components\ViewEntry::make('bodyHtml')
                            ->view('filament.infolists.components.iframe')
                            ->label('Message')
                            ->columnSpanFull(),
                    ]),
            ],
        });
    }

    protected static function mailLogKind(MailLog $record): string
    {
        if ($record->calendar) {
            return 'calendar';
        }

        return 'mail';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordClasses(fn (MailLog $record): ?string => match ($record->isUnread()) {
                false => 'opacity-80 hover:opacity-100',
                default => null,
            })
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\IconColumn::make('done')
                        ->boolean()
                        ->color(fn (MailLog $record): ?array => $record->isUnread()
                            ? Color::Sky
                            : null
                        )
                        ->grow(false),
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('to')
                            ->prefix(new HtmlString('<dfn class="not-italic font-medium">To:</dfn> '))
                            ->icon(fn (MailLog $record) => $record->booking
                                ? 'heroicon-o-calendar-days'
                                : null
                            )
                            ->state(fn (MailLog $record) => $record->booking
                                ? $record->booking->summary
                                : $record->to
                            )
                            ->searchable(),
                        Tables\Columns\TextColumn::make('from')
                            ->prefix(new HtmlString('<dfn class="not-italic font-medium">From:</dfn> '))
                            ->icon(fn (MailLog $record) => $record->user
                                ? 'heroicon-o-user'
                                : null
                            )
                            ->state(fn (MailLog $record): string => $record->user
                                ? $record->user->name.' <'.$record->user->email.'>'
                                : $record->from
                            )
                            ->searchable(),
                        Tables\Columns\TextColumn::make('created_at')
                            ->label('Received')
                            ->prefix(new HtmlString('<dfn class="not-italic font-medium">Received:</dfn> '))
                            ->dateTime(),
                    ])->alignLeft(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('done')
                    ->boolean(),
                Tables\Filters\TernaryFilter::make('unread')
                    ->boolean()
                    ->queries(
                        true: fn (Builder $query): Builder => $query->unread(),
                        false: fn (Builder $query): Builder => $query->whereNotNull('read_at')
                    ),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('read')
                        ->label('Mark selected read')
                        ->icon('heroicon-o-envelope-open')
                        ->visible(function (Pages\ListMailLogs $livewire): bool {
                            $anyUnread = false;
                            foreach ($livewire->getTableRecords() as $record) {
                                $anyUnread = $anyUnread || $record->isUnread();
                            }

                            return $anyUnread;
                        })
                        ->action(fn (Collection $records) => $records->each(fn (MailLog $record) => $record->markRead()->save())),
                    Tables\Actions\BulkAction::make('unread')
                        ->label('Mark selected unread')
                        ->icon('heroicon-o-envelope')
                        ->visible(function (Pages\ListMailLogs $livewire): bool {
                            $anyRead = false;
                            foreach ($livewire->getTableRecords() as $record) {
                                $anyRead = $anyRead || ! $record->isUnread();
                            }

                            return $anyRead;
                        })
                        ->action(fn (Collection $records) => $records->each(fn (MailLog $record) => $record->markUnread()->save())),
                ]),
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
            'index' => Pages\ListMailLogs::route('/'),
            'view' => Pages\ViewMailLog::route('/{record}'),
        ];
    }
}
