<?php

namespace App\Filament\Resources;

use App\Enums\BookingAttendeeStatus;
use App\Filament\Clusters\Admin\Resources\BookingResource as AdminBookingResource;
use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Facades\Filament;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $activeNavigationIcon = 'heroicon-s-calendar-days';

    protected static ?string $recordTitleAttribute = 'summary';

    public static function getGloballySearchableAttributes(): array
    {
        return ['activity', 'group_name'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Location' => $record->location,
            'Group' => $record->group_name,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        if ($currentUser = Filament::auth()->user()) {
            $count = Booking::future()->attendeeStatus($currentUser, BookingAttendeeStatus::NeedsAction)->count();

            return $count > 0 ? $count : null;
        }

        return null;
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Invitations';
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return BookingAttendeeStatus::NeedsAction->getColor();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return AdminBookingResource::infolist($infolist);
    }

    public static function table(Table $table): Table
    {
        return AdminBookingResource::table($table)
            ->actions([]);
    }

    public static function getRelations(): array
    {
        return [
            'guest-list' => BookingResource\RelationManagers\GuestListRelationManager::class,
        ];
    }

    public static function canViewAny(): bool
    {
        return static::can('viewOwn');
    }

    public static function authorizeViewAny(): void
    {
        static::authorize('viewOwn');
    }

    public static function canView(Model $record): bool
    {
        return static::can('preview', $record);
    }

    public static function authorizeView(Model $record): void
    {
        static::authorize('preview', $record);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'goto' => Pages\GotoBooking::route('/{record}/goto'),
        ];
    }
}
