<?php

namespace App\Filament\Clusters\Admin\Resources\BookingResource\Pages;

use App\Enums\BookingAttendeeStatus;
use App\Enums\BookingStatus;
use App\Filament\Clusters\Admin\Resources\BookingResource;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Models\Booking;
use App\Models\BookingAttendance;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditBooking extends EditRecord
{
    use Concerns\MutatesFormData, HasClusterSidebarNavigation;

    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\Action::make('status-cancel')
                ->hidden(fn (Booking $record) => $record->isCancelled())
                ->requiresConfirmation()
                ->label('Cancel')
                ->modalHeading(fn (): string => __('Cancel :label', ['label' => $this->getRecordTitle()]))
                ->modalDescription('When you cancel this booking all attendees will be notified by email, and anyone who has not yet responded will be removed from the guest list. You will not be able to make any changes to the booking once it has been cancelled.')
                ->icon(BookingStatus::Cancelled->getIcon())
                ->color(BookingStatus::Cancelled->getColor())
                ->action(function (Booking $record) {
                    DB::transaction(function () use ($record) {
                        // Remove attendees with outstanding invites
                        BookingAttendance::where('booking_id', $record->id)
                            ->where('status', BookingAttendeeStatus::NeedsAction)
                            ->delete();

                        $record->status = BookingStatus::Cancelled;
                        $record->save();
                    });

                    Notification::make()
                        ->title('Booking cancelled')
                        ->success()
                        ->send();
                }),
            Actions\DeleteAction::make()
                ->visible(fn (Booking $record) => $record->isCancelled()),
        ];
    }

    public function hasCombinedRelationManagerTabsWithContent(): bool
    {
        return true;
    }

    public function getContentTabLabel(): ?string
    {
        return 'Booking Details';
    }
}
