<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Panel;

class PrivacyPolicy extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $activeNavigationIcon = 'heroicon-s-document-text';

    protected static ?string $navigationGroup = 'Policies';

    protected static string $view = 'filament.pages.privacy-policy';

    protected static string|array $withoutRouteMiddleware = ['auth'];

    public static function isEmailVerificationRequired(Panel $panel): bool
    {
        return false;
    }
}
