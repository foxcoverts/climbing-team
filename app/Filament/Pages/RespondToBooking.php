<?php

namespace App\Filament\Pages;

use App\Actions\RespondToBookingAction;
use App\Enums\BookingAttendeeResponse;
use App\Filament\Resources\BookingResource\Pages\ViewBooking;
use App\Http\Middleware\AuthenticateFromParam;
use App\Models\Booking;
use App\Models\BookingAttendance;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use InvalidArgumentException;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\Response;

use function Filament\Support\is_app_url;

class RespondToBooking extends Page
{
    use HasUnsavedDataChangesAlert, InteractsWithFormActions;

    protected static string $layout = 'filament-panels::components.layout.simple';

    protected static string $view = 'filament.pages.respond-to-booking';

    protected static ?string $slug = 'respond/{booking}/{attendee}/{response?}';

    protected static bool $shouldRegisterNavigation = false;

    protected static string|array $routeMiddleware = [
        AuthenticateFromParam::class.':attendee',
    ];

    protected static string|array $withoutRouteMiddleware = ['auth'];

    #[Url]
    public Booking $booking;

    #[Url]
    public User $attendee;

    #[Url]
    public ?BookingAttendeeResponse $response = null;

    #[Url]
    public ?string $invite = null;

    #[Url]
    public int $sequence = 0;

    public ?array $data = [];

    public function mount(): void
    {
        try {
            new RespondToBookingAction($this->booking);
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        }

        $this->authorizeAccess();

        $this->fillForm();
    }

    protected function authorizeAccess(): void
    {
        if ($this->invite != $this->attendance()->token) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation invalid'));
        }

        if (! $this->attendee->is(Auth::user())) {
            // This should be handled by AuthenticateFromParam, which works when the page loads, but not when the form is submitted.
            Auth::onceUsingId($this->attendee->id);
        }

        abort_unless(Gate::check('respond', [$this->booking, $this->attendee]), 403);
    }

    protected function fillForm(): void
    {
        $data = $this->booking->attributesToArray();
        $data['response'] = $this->getResponse();

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->booking)
            ->operation('edit')
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('This booking has changed!')
                    ->icon('heroicon-o-information-circle')
                    ->iconColor('info')
                    ->visible(fn (Booking $record) => $this->sequence != $record->sequence)
                    ->schema([
                        Forms\Components\Placeholder::make('sequence')
                            ->hiddenLabel()
                            ->content('Check the details below before you confirm your attendance.'),
                    ]),
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
                            ->label('Can you attend this event?')
                            ->options(BookingAttendeeResponse::class)
                            ->required()->inline(),
                        Forms\Components\Placeholder::make('attendee.name')
                            ->hiddenLabel()
                            ->content(fn () => __('Responding for :attendee.name.', [
                                'attendee.name' => $this->attendee->name,
                            ])),
                    ]),
            ]);
    }

    public function save(): void
    {
        $this->authorizeAccess();

        try {
            $data = $this->form->getState();

            $respondToBooking = new RespondToBookingAction($this->booking, $this->attendee);
            $respondToBooking($this->attendee, $data['response']);

            Notification::make()
                ->success()
                ->title('Attendance updated')
                ->send();

            $redirectUrl = ViewBooking::getUrl(['record' => $this->booking]);
            $this->redirect($redirectUrl, navigate: FilamentView::hasSpaMode() && is_app_url($redirectUrl));
        } catch (InvalidArgumentException $e) {
            abort(Response::HTTP_FORBIDDEN, __('Invitation expired'));
        } catch (Halt $e) {
            Notification::make()
                ->danger()
                ->title('Attendance not recorded')
                ->send();

            return;
        }
    }

    public function getFormActions(): array
    {
        return [
            $this->getSaveFormAction(),
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->record($this->booking)
            ->label('Respond')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public static function isEmailVerificationRequired(Panel $panel): bool
    {
        return false;
    }

    public static function isTenantSubscriptionRequired(Panel $panel): bool
    {
        return false;
    }

    public function attendance(): BookingAttendance
    {
        if (! $this->attendee->relationLoaded('attendance')) {
            $this->attendee = $this->booking->attendees()->find($this->attendee);
        }

        return $this->attendee->attendance;
    }

    protected function getResponse(): ?BookingAttendeeResponse
    {
        if ($this->response) {
            return $this->response;
        }

        if ($this->attendance()->status) {
            return BookingAttendeeResponse::tryFromStatus($this->attendance()->status);
        }

        return null;
    }

    /*
     * Simple Page Requirements
     */
    public function hasLogo(): bool
    {
        return true;
    }

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => false,
            'maxWidth' => MaxWidth::TwoExtraLarge,
        ];
    }
}
