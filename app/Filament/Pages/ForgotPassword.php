<?php

namespace App\Filament\Pages;

use App\Models\User;
use Carbon\Carbon;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use IPPanel\Client;

class ForgotPassword extends BaseLogin
{
    public ?array $data = null;

    public function mount(): void
    {
        parent::mount();

        $this->data = [
            'mobile_number' => '',
            'otp_code' => '',
            'password' => '',
            'password_confirmation' => '',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('mobile_number')
                    ->label('شماره موبایل')
                    ->tel()
                    ->required()
                    ->minLength(11)
                    ->maxLength(11)
                    ->regex('/^09[0-9]{9}$/')
                    ->rule('exists:users,mobile')
                    ->suffixAction(
                        Action::make('send_otp')
                            ->label('ارسال کد')
                            ->icon('heroicon-o-chat-bubble-left-ellipsis')
                            ->action(fn () => $this->sendOtp())
                    ),

                TextInput::make('otp_code')
                    ->label('کد تأیید')
                    ->numeric()
                    ->required()
                    ->minLength(4)
                    ->maxLength(6),

                TextInput::make('password')
                    ->label('رمز عبور جدید')
                    ->password()
                    ->required()
                    ->minLength(6),

                TextInput::make('password_confirmation')
                    ->password()
                    ->label('تکرار رمز عبور')
                    ->required()
                    ->same('password'),
            ])
            ->statePath('data');
    }

    public function sendOtp(): void
    {
        $now = Carbon::now();
        $mobile = $this->data['mobile_number'];

        if (!preg_match('/^09\d{9}$/', $mobile)) {
            Notification::make()
                ->title('خطا')
                ->body('شماره موبایل نامعتبر است.')
                ->danger()
                ->send();
            return;
        }

        $user = User::where('mobile', $mobile)->first();

        if (! $user) {
            Notification::make()
                ->title('خطا')
                ->body('کاربری با این شماره وجود ندارد.')
                ->danger()
                ->send();
            return;
        }

        // چک برای cooldown
        $expiresAt = session('otp_expires_at');
        $nextRequestAllowedAt = session('otp_next_request_allowed_at');

        if ($expiresAt && $nextRequestAllowedAt && $now->lt($expiresAt) && $now->lt($nextRequestAllowedAt)) {
            $remaining = (int) $now->diffInSeconds($nextRequestAllowedAt);
            Notification::make()
                ->title('لطفاً صبر کنید')
                ->body("کد قبلاً ارسال شده است. لطفاً $remaining ثانیه دیگر تلاش کنید.")
                ->warning()
                ->send();
            return;
        }

        // تولید و ذخیره OTP
        $otpLength = (int) env('IPPANEL_OTP_DIGITS', 4);
        $otpCode = rand(pow(10, $otpLength - 1), pow(10, $otpLength) - 1);

        session()->put('otp_code', $otpCode);
        session()->put('otp_expires_at', $now->copy()->addMinutes(5));
        session()->put('otp_next_request_allowed_at', $now->copy()->addMinutes(3));

        try {
            $client = new Client(env('IPPANEL_API_KEY'));
            $client->sendPattern(
                env('IPPANEL_PATTERN'),
                env('IPPANEL_ORIGIN_NUMBER'),
                '+98' . substr($mobile, 1),
                ['code' => $otpCode]
            );

            Notification::make()
                ->title('موفق')
                ->body('کد تأیید با موفقیت ارسال شد.')
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title('خطا در ارسال پیامک')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function authenticate(): ?LoginResponse
    {
        $this->validate();

        $mobile = $this->data['mobile_number'];
        $otpCode = $this->data['otp_code'];
        $password = $this->data['password'];

        $otpInSession = session('otp_code');
        $expiresAt = session('otp_expires_at');

        if (!$otpInSession || !$expiresAt || now()->gt($expiresAt)) {
            Notification::make()
                ->title('کد منقضی شده است')
                ->body('لطفاً دوباره درخواست کد کنید.')
                ->danger()
                ->send();
            return null;
        }

        if ($otpCode != $otpInSession) {
            Notification::make()
                ->title('کد اشتباه است')
                ->body('کد وارد شده نادرست است.')
                ->danger()
                ->send();
            return null;
        }

        $user = User::where('mobile', $mobile)->first();

        if (! $user) {
            Notification::make()
                ->title('خطا')
                ->body('کاربر یافت نشد.')
                ->danger()
                ->send();
            return null;
        }

        $user->password = Hash::make($password);
        $user->save();

        session()->forget(['otp_code', 'otp_expires_at', 'otp_next_request_allowed_at']);

        Auth::login($user);

        Notification::make()
            ->title('رمز با موفقیت تغییر کرد')
            ->body('اکنون وارد پنل شده‌اید.')
            ->success()
            ->send();

        return app(LoginResponse::class);
    }
}
