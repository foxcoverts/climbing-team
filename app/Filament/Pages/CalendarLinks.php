<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Gate;
use Webbingbrasil\FilamentCopyActions\Forms\Actions\CopyAction;

class CalendarLinks extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $navigationGroup = 'Profile';

    protected static string $view = 'filament.pages.calendar-links';

    public ?array $data = [];

    public function mount(): void
    {
        $this->authorizeAccess();

        $this->fillForm();
    }

    public static function canAccess(): bool
    {
        return Filament::auth()->check();
    }

    protected function authorizeAccess(): void
    {
        abort_unless(Gate::check('view', $this->getRecord() ?? User::class), 403);
    }

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [
            static::getUrl() => static::getNavigationLabel(),
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        return $breadcrumbs;
    }

    protected function fillForm(): void
    {
        $record = $this->getRecord();

        $data = [
            'rota-link' => route('booking.rota.ics', $record),
            'calendar-link' => route('booking.ics', $record),
            ...$record->attributesToArray(),
        ];

        $this->form->fill($data);
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->getRecord())
            ->operation('view')
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make()
                    ->heading('Use these links to subscribe to your bookings from other applications.')
                    ->description('Warning: These links are unique to your account, do not share them with other people.')
                    ->schema([
                        Forms\Components\TextInput::make('rota-link')
                            ->readOnly()
                            ->suffixAction(CopyAction::make()->copyable(fn ($state) => $state)),
                        Forms\Components\TextInput::make('calendar-link')
                            ->readOnly()
                            ->suffixAction(CopyAction::make()->copyable(fn ($state) => $state)),
                    ]),
            ]);
    }

    public function resetLinks(): void
    {
        $record = $this->getRecord();
        Gate::authorize('update', $record);

        try {
            $this->resetUserToken($record);
        } catch (Halt $exception) {
            return;
        }

        $this->fillForm();

        Notification::make()
            ->success()
            ->title(__('Your calendar links have been reset.'))
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            $this->getResetLinksAction(),
        ];
    }

    public function getResetLinksAction(): Action
    {
        return Action::make('reset-links')
            ->record($this->getRecord())
            ->authorize('update', $this->getRecord())
            ->requiresConfirmation()
            ->modalDescription('You can reset these links and make the current ones invalid. You will need to update any applications using these links. Are you sure you want to do this?')
            ->modalSubmitActionLabel('Reset')
            ->label('Reset Links')
            ->icon('heroicon-o-link-slash')
            ->color('danger')
            ->action(fn () => $this->resetLinks());
    }

    public function getRecord(): ?User
    {
        return $this->ensureUserHasToken(Filament::auth()->user());
    }

    protected function ensureUserHasToken(User $user): User
    {
        if (is_null($user->ical_token)) {
            $this->resetUserToken($user);
        }

        return $user;
    }

    protected function resetUserToken(User $user): void
    {
        $user->update(['ical_token' => $user::generateToken()]);
    }
}
