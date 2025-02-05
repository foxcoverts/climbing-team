<?php

namespace App\Filament\Actions\Concerns;

use App\Actions\RespondToBookingAction;
use App\Enums\BookingAttendeeResponse;
use App\Enums\BookingAttendeeStatus;
use App\Models\Booking;
use Exception;
use Filament\Actions\Concerns\CanCustomizeProcess;
use Filament\Actions\StaticAction;
use Filament\Facades\Filament;
use Filament\Forms;

trait RespondAction
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'respond';
    }

    protected static function getAttendeeStatus(Booking $booking): ?BookingAttendeeStatus
    {
        $attendee = Filament::auth()->user();
        $attendee = $booking->attendees()->find($attendee);

        return $attendee?->attendance->status;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalHeading(fn (): string => __('Respond to :Label', ['label' => $this->getRecordTitle()]));

        $this->successNotificationTitle('Attendance updated');

        $this->form([
            Forms\Components\Section::make(fn (Booking $record) => __(':Status Booking', [
                'status' => $record->status->getLabel(),
            ]))
                ->icon(fn (Booking $record) => $record->status->getIcon())
                ->iconColor(fn (Booking $record) => $record->status->getColor())
                ->schema([
                    Forms\Components\Placeholder::make('when')
                        ->content(fn (Booking $record) => __(':date from :start_time to :end_time (:duration)', [
                            'date' => $record->start_at->timezone($record->timezone)->toFormattedDayDateString(),
                            'start_time' => $record->start_at->timezone($record->timezone)->format('H:i'),
                            'end_time' => $record->end_at->timezone($record->timezone)->format('H:i'),
                            'duration' => $record->start_at->diffAsCarbonInterval($record->end_at),
                        ])),
                    Forms\Components\Placeholder::make('location')
                        ->content(fn (Booking $record) => $record->location),
                    Forms\Components\Placeholder::make('activity')
                        ->content(fn (Booking $record) => $record->activity),
                    Forms\Components\Placeholder::make('group')
                        ->content(fn (Booking $record) => $record->group_name),
                    Forms\Components\Placeholder::make('notes')
                        ->hidden(fn (Booking $record) => blank($record->notes))
                        ->content(fn (Booking $record) => str($record->notes)->markdown()->toHtmlString()),
                    Forms\Components\Placeholder::make('lead_instructor')
                        ->hidden(fn (Booking $record) => blank($record->lead_instructor))
                        ->content(fn (Booking $record) => $record->lead_instructor?->name),
                ]),
            Forms\Components\Section::make()
                ->schema([
                    Forms\Components\ToggleButtons::make('response')
                        ->formatStateUsing(fn (Booking $record) => BookingAttendeeResponse::tryFromStatus(static::getAttendeeStatus($record)))
                        ->label('Can you attend this event?')
                        ->options(BookingAttendeeResponse::class)
                        ->required()->inline(),
                ]),
        ]);

        $this->action(fn (array $data) => $this->save($data));

        $this->modalSubmitAction(fn (StaticAction $action) => $action
            ->label('Respond')
            ->color('primary')
        );
    }

    public function useStatusLabel(): static
    {
        $this
            ->label(fn (?BookingAttendeeStatus $status) => match ($status) {
                BookingAttendeeStatus::NeedsAction, null => 'Respond',
                default => $status->getLabel()
            })
            ->icon(fn (?BookingAttendeeStatus $status) => match ($status) {
                BookingAttendeeStatus::NeedsAction, null => null,
                default => $status->getIcon()
            })
            ->color(fn (?BookingAttendeeStatus $status) => match ($status) {
                BookingAttendeeStatus::NeedsAction, null => null,
                default => $status->getColor()
            })
            ->button();

        return $this;
    }

    public function save(array $data): void
    {
        try {
            $attendee = Filament::auth()->user();

            $respondToBooking = new RespondToBookingAction($this->getRecord());
            $respondToBooking($attendee, $data['response']);

            $this->success();
        } catch (Exception $e) {
            $this->fail();
        }
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
