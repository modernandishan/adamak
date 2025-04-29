<?php

use App\Filament\Pages\ForgotPassword;

use App\Filament\Pages\ProfileEdit;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use IPPanel\Client;

Route::get('/', function () {
    return redirect('/adamak/login');
});
Route::get('/forgot-password', ForgotPassword::class)->name('filament.pages.forgot-password');
Route::get('/wallet/callback', [WalletController::class, 'callback'])->name('wallet.callback');
Route::post('/send-to-gateway', [WalletController::class, 'SendToGateway'])->name('send.to.gateway');
