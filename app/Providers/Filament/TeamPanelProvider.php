<?php

namespace App\Providers\Filament;

use App\Filament\Clusters;
use App\Filament\Clusters\My\Pages\ChangePassword;
use App\Filament\Clusters\My\Pages\EditProfile;
use App\Filament\Pages;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RalphJSmit\Filament\Activitylog\FilamentActivitylog;

class TeamPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('team')
            ->path('team')
            ->default()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/team/theme.css')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            ->plugin(FilamentActivitylog::make())
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(Clusters\My::getNavigationLabel()),
                NavigationGroup::make()
                    ->label(Clusters\Admin::getNavigationLabel()),
                NavigationGroup::make()
                    ->label(Clusters\Developer::getNavigationLabel()),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->url(fn (): string => EditProfile::getUrl()),
                'password' => MenuItem::make()
                    ->label('Change Password')
                    ->icon('heroicon-o-lock-closed')
                    ->url(fn (): string => ChangePassword::getUrl()),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->spa();
    }
}
