<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use BezhanSalleh\FilamentLanguageSwitch\Enums\Placement;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Facades\Filament;
use Filament\Forms\Components\DateTimePicker;
use Filament\Infolists\Infolist;
use Filament\Navigation\UserMenuItem;
use Filament\Tables\Table;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                'wallet' => UserMenuItem::make()
                    ->label('کیف پول من')
                    ->url(route('filament.admin.pages.wallet-charge')) // یا هر مسیر صفحه شارژ
                    ->icon('heroicon-o-banknotes'),
            ]);
        });        Table::$defaultDateDisplayFormat = 'Y/m/d';
        Table::$defaultDateTimeDisplayFormat = 'Y/m/d H:i:s';

        Infolist::$defaultDateDisplayFormat = 'Y/m/d';
        Infolist::$defaultDateTimeDisplayFormat = 'Y/m/d H:i:s';

        DateTimePicker::$defaultDateDisplayFormat = 'Y/m/d';
        DateTimePicker::$defaultDateTimeDisplayFormat = 'Y/m/d H:i';
        DateTimePicker::$defaultDateTimeWithSecondsDisplayFormat = 'Y/m/d H:i:s';

        User::observe(UserObserver::class);
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['fa', 'en'])
                ->visible(outsidePanels: true)
                ->outsidePanelRoutes(['login', 'register']) // مسیرهای مورد نظر
                ->outsidePanelPlacement(Placement::BottomRight); // محل نمایش سوئیچ زبان
        });
    }
}
