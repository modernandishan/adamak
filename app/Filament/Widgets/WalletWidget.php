<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WalletWidget extends Widget
{
    protected static string $view = 'filament.widgets.wallet-widget';
    protected static bool $isLazy = false;

    protected static ?int $sort = -2;

    public static function canView(): bool
    {
        return auth()->check();
    }
}
