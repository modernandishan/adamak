<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Auth;
use Shetabit\Multipay\Payment;
use Shetabit\Multipay\Exceptions\InvalidPaymentException;
use Shetabit\Multipay\Invoice;
use Filament\Notifications\Notification;

class WalletController extends Controller
{
    public function SendToGateway(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);

        $amount = (int) $request->input('amount');

        $payment = new Payment(config('payment'));

        $invoice = (new Invoice)->amount($amount);

        return $payment
            ->callbackUrl(route('wallet.callback'))
            ->purchase($invoice, function ($driver, $transactionId) use ($amount) {
                $user = Auth::user();
                $wallet = $user->wallet()->firstOrCreate(
                    ['user_id' => $user->id],
                    ['balance' => 0]
                );
                $wallet->transactions()->create([
                    'transaction_id' => $transactionId,
                    'amount' => $amount,
                    'type' => 'charge',
                    'description' => 'در انتظار پرداخت',
                    'status' => 'pending',
                ]);
            })
            ->pay()
            ->render();


        $redirectForm = $purchase->pay();

        $actionUrl = $redirectForm->getAction();

        return redirect()->away($actionUrl);
    }



    public function callback(Request $request)
    {
        $status = $request->input('Status');
        $authority = $request->input('Authority');

        if (!$status || !$authority) {
            abort(400, 'اطلاعات بازگشتی نامعتبر است.');
        }

        $transaction = WalletTransaction::where('transaction_id', $authority)
            ->where('status', 'pending')
            ->first();

        if (!$transaction) {
            abort(404, 'تراکنش مورد نظر یافت نشد.');
        }

        if ($status !== 'OK') {
            // کاربر پرداخت را لغو کرده یا خطایی رخ داده
            $transaction->update([
                'status' => 'failed',
                'description' => 'پرداخت توسط کاربر لغو شد یا تایید نشد.',
            ]);

            session()->flash('filament.notifications', [
                [
                    'title' => 'پرداخت لغو شد',
                    'body' => 'شما پرداخت را لغو کردید یا عملیات ناموفق بود.',
                    'status' => 'danger',
                ]
            ]);

            return redirect('/adamak/wallet-charge');
        }

        $payment = new Payment(config('payment'));

        try {
            $receipt = $payment->amount($transaction->amount)->transactionId($authority)->verify();

            $transaction->update([
                'status' => 'completed',
                'tracking_code' => (int) $receipt->getReferenceId(),
                'description' => 'پرداخت کامل شد.',
            ]);

            $wallet = $transaction->wallet;
            $wallet->increment('balance', $transaction->amount);

            session()->flash('filament.notifications', [
                [
                    'title' => 'پرداخت موفق',
                    'body' => 'پرداخت شما با موفقیت انجام شد و کیف پول شارژ گردید.',
                    'status' => 'success',
                ]
            ]);

        } catch (InvalidPaymentException $e) {
            $transaction->update([
                'status' => 'failed',
                'description' => 'خطا در پرداخت: ' . $e->getMessage(),
            ]);

            session()->flash('filament.notifications', [
                [
                    'title' => 'پرداخت ناموفق',
                    'body' => 'پرداخت انجام نشد. لطفاً مجدد تلاش کنید.',
                    'status' => 'danger',
                ]
            ]);
        }

        return redirect('/adamak/wallet-charge');
    }


}
