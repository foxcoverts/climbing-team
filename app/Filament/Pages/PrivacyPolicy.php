<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PrivacyPolicy extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Policies';

    protected static string $view = 'filament.pages.privacy-policy';
}
