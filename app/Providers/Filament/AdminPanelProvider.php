<?php

namespace App\Providers\Filament;

use App\Filament\Pages\FillTestResponse;
use App\Filament\Pages\ListAvailableTests;
use App\Filament\Pages\Login;
use App\Filament\Pages\ProfileEdit;
use App\Filament\Pages\Register;
use App\Filament\Resources\FamilyResource;
use App\Filament\Widgets\WalletWidget;
use App\Http\Middleware\EnsureMobileIsVerified;
use Filament\FontProviders\LocalFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('adamak')
            ->login(Login::class)
            ->registration(Register::class)
            ->font(
                'Vazir',
                url: asset('css/filament/custom.css'),
                provider: LocalFontProvider::class,
            )
            ->breadcrumbs()
            ->userMenuItems([

                MenuItem::make()
                    ->label(fn () => __('profile.page_title'))
                    ->url(fn () => route('filament.admin.pages.profile-edit'))
                    ->icon('heroicon-o-user-circle'),
            ])

            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                ProfileEdit::class,
                ListAvailableTests::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
                WalletWidget::class,
            ])
            ->plugins([
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
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
                EnsureMobileIsVerified::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
