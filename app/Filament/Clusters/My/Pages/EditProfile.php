<?php

namespace App\Filament\Clusters\My\Pages;

use App\Filament\Clusters\My;
use App\Filament\Pages\Concerns\HasClusterSidebarNavigation;
use App\Models\User;
use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Support\Colors\Color;
use Filament\Support\Exceptions\Halt;
use Illuminate\Support\Facades\Auth;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class EditProfile extends Page implements HasForms
{
    use HasClusterSidebarNavigation, InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $navigationLabel = 'Profile';

    protected static ?string $slug = 'profile';

    protected static string $view = 'filament.clusters.my.pages.edit-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->user()->attributesToArray());
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
            ]);
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();

            $this->user()->update($data);
        } catch (Halt $exception) {
            return;
        }

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

    protected function user(): User
    {
        return Auth::user();
    }
}
