<?php

namespace App\Providers\Filament;

use App\Filament\Clusters;
use App\Filament\Pages;
use Filament\Facades\Filament;
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
use RalphJSmit\Filament\Onboard;
use RalphJSmit\Filament\Onboard\FilamentOnboard;
use RalphJSmit\Filament\Onboard\Http\Middleware\OnboardMiddleware;
use RalphJSmit\Filament\Onboard\Widgets\OnboardTrackWidget;

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
            ->plugins([
                FilamentActivitylog::make(),
                FilamentOnboard::make()
                    ->prefix('welcome')
                    ->addTrack(fn () => Onboard\Track::make([
                        Onboard\Step::make('Emergency Contact', 'onboard::emergency-contact')
                            ->description('You have not provided any emergency contact details, there may be a delay in contacting someone if necessary.')
                            ->performStepActionLabel('Edit Contacts')
                            ->url(Pages\EditProfile::getUrl())
                            ->completeIf(fn (): bool => filled(Filament::auth()->user()->emergency_phone)),
                    ])),
            ])
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                OnboardTrackWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(Clusters\Admin::getNavigationLabel()),
                NavigationGroup::make()
                    ->label(Clusters\Developer::getNavigationLabel()),
                NavigationGroup::make()
                    ->label('Profile'),
                NavigationGroup::make()
                    ->label('Policies')
                    ->collapsed(),
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->url(fn (): string => Pages\EditProfile::getUrl()),
                'password' => MenuItem::make()
                    ->label(Pages\ChangePassword::getNavigationLabel())
                    ->icon(Pages\ChangePassword::getNavigationIcon())
                    ->url(fn (): string => Pages\ChangePassword::getUrl()),
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
                OnboardMiddleware::class,
            ])
            ->globalSearchKeyBindings(['mod+k'])
            ->globalSearchFieldKeyBindingSuffix()
            ->login()
            ->passwordReset()
            ->emailVerification()
            ->spa();
    }
}
