<?php

namespace App\Filament\Resources\BookingResource\Actions;

use App\Actions\RespondToBookingAction;
use App\Enums\BookingAttendeeStatus;
use App\Filament\Resources\BookingResource;
use App\Models\Booking;
use Filament\Actions;
use Filament\Actions\Concerns\InteractsWithRecord;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;

class RespondAction extends Actions\ActionGroup
{
    use InteractsWithRecord;

    protected static string $resource = BookingResource::class;

    protected ?BookingAttendeeStatus $status;

    public static function make(array $actions = []): static
    {
        return parent::make([
            ...$actions,
            static::makeAction(BookingAttendeeStatus::Accepted),
            static::makeAction(BookingAttendeeStatus::Tentative),
            static::makeAction(BookingAttendeeStatus::Declined),
        ]);
    }

    public static function makeAction(BookingAttendeeStatus $status): Actions\Action
    {
        return Actions\Action::make($status->value)
            ->hidden(fn (Booking $record): bool => static::getAttendeeStatus($record) === $status)
            ->label($status->getLabel())
            ->icon($status->getIcon())
            ->color($status->getColor())
            ->successRedirectUrl(fn (Booking $record) => static::$resource::getUrl('view', ['record' => $record]))
            ->successNotification(
                Notification::make()
                    ->success()
                    ->title('Attendance updated')
                    ->body($status->getLabel())
            )
            ->action(function (Actions\Action $action, Booking $record) use ($status): void {
                $respondToBooking = new RespondToBookingAction($record);
                $attendee = Filament::auth()->user();

                $respondToBooking($attendee, $status);
                $action->success();
            });
    }

    protected static function getAttendeeStatus(Booking $booking): ?BookingAttendeeStatus
    {
        $attendee = Filament::auth()->user();
        $attendee = $booking->attendees()->find($attendee);

        return $attendee?->attendance->status;
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->label(fn (?BookingAttendeeStatus $status) => $status?->getLabel() ?? 'Respond')
            ->icon(fn (?BookingAttendeeStatus $status) => ($status ?? BookingAttendeeStatus::NeedsAction)->getIcon())
            ->color(fn (?BookingAttendeeStatus $status) => ($status ?? BookingAttendeeStatus::NeedsAction)->getColor())
            ->button()
            ->hidden(fn () => Filament::auth()->guest());
    }

    protected function resolveDefaultClosureDependencyForEvaluationByName(string $parameterName): array
    {
        $record = $this->getRecord();

        if (! $record) {
            return parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName);
        }

        return match ($parameterName) {
            'status' => [static::getAttendeeStatus($record)],
            default => parent::resolveDefaultClosureDependencyForEvaluationByName($parameterName),
        };
    }
}
