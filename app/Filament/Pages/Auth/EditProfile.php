<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Ysfkaya\FilamentPhoneInput\Forms\PhoneInput;

class EditProfile extends BaseEditProfile
{
    public function form(Form $form): Form
    {
        return $form->schema([
            Components\Section::make('Contact Details')
                ->schema([
                    $this->getNameFormComponent(),
                    $this->getEmailFormComponent(),
                    PhoneInput::make('phone')
                        ->defaultCountry('GB')
                        ->validateFor(['INTERNATIONAL', 'GB'])
                        ->visible(fn (string $operation) => $operation === 'edit')
                        ->nullable(),
                ]),
            Components\Section::make('Emergency Contact')
                ->description('The lead instructor for a booking will be able to access these details should the need arise. If no details are provided then there may be a delay in contacting someone.')
                ->collapsed()
                ->visible(fn (string $operation) => $operation === 'edit')
                ->schema([
                    Components\TextInput::make('emergency_name')
                        ->maxLength(100)
                        ->requiredWith('emergency_phone')
                        ->nullable(),
                    PhoneInput::make('emergency_phone')
                        ->defaultCountry('GB')
                        ->validateFor(['INTERNATIONAL', 'GB'])
                        ->requiredWith('emergency_name')
                        ->nullable(),
                ]),
            Components\Section::make('Change Password')
                ->schema([
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                ]),
        ]);
    }
}
