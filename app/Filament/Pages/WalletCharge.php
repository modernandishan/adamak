<?php

namespace App\Filament\Pages;

use App\Models\WalletTransaction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Shetabit\Multipay\Invoice;
use Shetabit\Multipay\Payment;

class WalletCharge extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'شارژ کیف پول';
    protected static string $view = 'filament.pages.wallet-charge';



}
