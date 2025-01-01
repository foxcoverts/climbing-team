<?php

namespace App\Filament\Pages;

use App\Filament\Forms\Components as AppComponents;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasUnsavedDataChangesAlert;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use Filament\Support\Exceptions\Halt;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class EditProfile extends Page
{
    use HasUnsavedDataChangesAlert, InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $activeNavigationIcon = 'heroicon-s-user';

    protected static ?string $navigationGroup = 'Profile';

    protected static ?string $slug = 'profile';

    protected static string $view = 'filament.pages.edit-profile';

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
                Components\Section::make('Contact Details')
                    ->schema([
                        Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Components\TextInput::make('email')
                            ->email()
                            ->hint(fn (?User $record): ?string => match ($record?->hasVerifiedEmail()) {
                                true => 'Email Verified',
                                false => 'Email Unverified',
                                default => null,
                            })
                            ->hintIcon(fn (?User $record): ?string => match ($record?->hasVerifiedEmail()) {
                                true => 'heroicon-o-check-circle',
                                false => 'heroicon-o-x-circle',
                                default => null,
                            })
                            ->hintColor(fn (?User $record): ?array => match ($record?->hasVerifiedEmail()) {
                                true => Color::Lime,
                                false => Color::Pink,
                                default => null,
                            })
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255),
                        PhoneInput::make('phone')
                            ->defaultCountry('GB')
                            ->initialCountry('GB')
                            ->validateFor(['INTERNATIONAL', 'GB'])
                            ->nullable(),
                    ]),
                Components\Section::make('Emergency Contact')
                    ->description('The lead instructor for a booking will be able to access these details should the need arise. If no details are provided then there may be a delay in contacting someone.')
                    ->schema([
                        Components\TextInput::make('emergency_name')
                            ->maxLength(100)
                            ->requiredWith('emergency_phone')
                            ->nullable(),
                        PhoneInput::make('emergency_phone')
                            ->defaultCountry('GB')
                            ->initialCountry('GB')
                            ->validateFor(['INTERNATIONAL', 'GB'])
                            ->requiredWith('emergency_name')
                            ->nullable(),
                    ]),
                Components\Section::make('Settings')
                    ->schema([
                        AppComponents\TimezoneSelect::make('timezone')
                            ->searchable()
                            ->required()
                            ->defaultByBrowser(),
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
