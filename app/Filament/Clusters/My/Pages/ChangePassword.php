<?php

namespace App\Filament\Clusters\My\Pages;

use App\Rules\Password;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ChangePassword extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-lock-closed';

    protected static ?string $activeNavigationIcon = 'heroicon-s-lock-closed';

    protected static ?string $navigationLabel = 'Password';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.clusters.my.pages.change-password';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->getUser()->attributesToArray());
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

            $this->getUser()->update($data);
        } catch (Halt $exception) {
            return;
        }

        if (request()->hasSession() && array_key_exists('password', $data)) {
            request()->session()->put([
                'password_hash_'.Filament::getAuthGuard() => $data['password'],
            ]);
        }

        $this->data['currentPassword'] = null;
        $this->data['password'] = null;
        $this->data['passwordConfirmation'] = null;

        Notification::make()
            ->success()
            ->title(__('filament-panels::resources/pages/edit-record.notifications.saved.title'))
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Components\Actions\Action::make('save')
                ->label(__('filament-panels::resources/pages/edit-record.form.actions.save.label'))
                ->submit('save'),
        ];
    }

    public function getUser(): Authenticatable&Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }
}
