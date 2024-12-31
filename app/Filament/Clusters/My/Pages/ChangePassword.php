<?php

namespace App\Filament\Clusters\My\Pages;

use App\Models\User;
use App\Rules\Password;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Page
{
    use HasUnsavedDataChangesAlert, InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $activeNavigationIcon = 'heroicon-s-lock-closed';

    protected static ?string $navigationLabel = 'Password';

    protected static ?int $navigationSort = 5;

    protected static string $view = 'filament.clusters.my.pages.change-password';

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = $this->getRecord()?->attributesToArray();

        $this->form->fill($data);
    }

    public function getBreadcrumbs(): array
    {
        $breadcrumbs = [
            static::getUrl() => static::getNavigationLabel(),
            'Edit',
        ];

        if (filled($cluster = static::getCluster())) {
            return $cluster::unshiftClusterBreadcrumbs($breadcrumbs);
        }

        return $breadcrumbs;
    }

    public function form(Form $form): Form
    {
        return $form
            ->model($this->getRecord())
            ->operation('edit')
            ->statePath('data')
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\TextInput::make('currentPassword')
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required()
                            ->autocomplete('password')
                            ->dehydrated(false)
                            ->currentPassword(),

                        Components\TextInput::make('password')
                            ->label(__('filament-panels::pages/auth/edit-profile.form.password.label'))
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->rule(Password::default())
                            ->autocomplete('new-password')
                            ->dehydrated(fn ($state): bool => filled($state))
                            ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                            ->required()
                            ->same('passwordConfirmation'),

                        Components\TextInput::make('passwordConfirmation')
                            ->label(__('filament-panels::pages/auth/edit-profile.form.password_confirmation.label'))
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->required()
                            ->dehydrated(false),
                    ]),
            ]);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $this->getRecord()->update($data);
        } catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->fillForm();

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
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
            ->record($this->getRecord())
            ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    public function getRecord(): ?User
    {
        return Filament::auth()->user();
    }
}
