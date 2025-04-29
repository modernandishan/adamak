<?php

namespace App\Filament\Pages;

use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Carbon\Carbon;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Actions\Action;
use IPPanel\Client;

class ProfileEdit extends Page implements HasForms
{
    use InteractsWithForms;
    use HasPageShield;
    public function getTitle(): string
    {
        return __('profile.page_title');
    }

    public static function getNavigationLabel(): string
    {
        return __('profile.page_title');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    protected static string $view = 'filament.pages.profile-edit';

    public ?array $data = [];

    public function mount(): void
    {
        $user = Auth::user()->load('profile');

        $this->form->fill([
            'first_name'       => $user->first_name,
            'last_name'        => $user->last_name,
            'mobile'           => $user->mobile,
            'relationship'     => $user->profile->relationship ?? '',
            'province'         => $user->profile->province ?? '',
            'city'             => $user->profile->city ?? '',
            'address'          => $user->profile->address ?? '',
            'gender'           => $user->profile->gender ?? '',
            'postal_code'      => $user->profile->postal_code ?? '',
        ]);

        $this->data['mobile_verified'] = $user->mobile_verified_at ? true : false;
        $this->data['otp_code'] = '';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('profile.user_info'))
                    ->description(__('profile.user_info_description'))
                    ->schema([
                        TextInput::make('first_name')->label(__('profile.first_name'))->required(),
                        TextInput::make('last_name')->label(__('profile.last_name'))->required(),
                        TextInput::make('mobile')
                            ->label(__('profile.mobile'))
                            ->tel()
                            ->disabled(fn () => $this->data['mobile_verified'])
                            ->suffixAction(
                                fn () => !$this->data['mobile_verified']
                                    ? Action::make('send_otp')
                                        ->label(__('profile.send_otp'))
                                        ->icon('heroicon-o-chat-bubble-left-ellipsis')
                                        ->action(fn () => $this->sendOtp())
                                    : null
                            ),

                        TextInput::make('otp_code')
                            ->label(__('profile.otp_code'))
                            ->numeric()
                            ->minLength(4)
                            ->maxLength(6)
                            ->visible(fn () => !$this->data['mobile_verified']),
                        Select::make('gender')
                            ->label(__('profile.gender'))
                            ->options([
                                'مرد' => __('profile.male'),
                                'زن' => __('profile.female'),
                            ]),
                        Select::make('relationship')
                            ->label(__('profile.relationship'))
                            ->options([
                                'مادر' => __('profile.mother'),
                                'پدر' => __('profile.father'),
                                'برادر' => __('profile.brother'),
                                'خواهر' => __('profile.sister'),
                                'خاله' => __('profile.khale'),
                                'دایی' => __('profile.daee'),
                                'عمه' => __('profile.ameh'),
                                'عمو' => __('profile.amoo'),
                                'پدربرزگ' => __('profile.grandfather'),
                                'مادربزرگ' => __('profile.grandmother'),
                                'سرپرست' => __('profile.guardian'),
                                'سایر' => __('profile.other'),
                            ])
                            ->native(false)
                            ->required(),
                    ])
                    ->columns(2),

                Section::make(__('profile.address_info'))
                    ->description(__('profile.address_info_description'))
                    ->schema([
                        TextInput::make('province')->label(__('profile.province')),
                        TextInput::make('city')->label(__('profile.city')),
                        Textarea::make('address')->label(__('profile.address'))->rows(3)->columnSpan('full'),
                        TextInput::make('postal_code')->label(__('profile.postal_code')),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function sendOtp(): void
    {
        $now = Carbon::now();
        $mobile = $this->data['mobile'];

        if (!preg_match('/^09\d{9}$/', $mobile)) {
            Notification::make()
                ->title(__('profile.error'))
                ->body(__('profile.invalid_mobile'))
                ->danger()
                ->send();
            return;
        }

        // cooldown check
        $expiresAt = session('otp_expires_at');
        $nextRequestAllowedAt = session('otp_next_request_allowed_at');

        if ($expiresAt && $nextRequestAllowedAt && $now->lt($expiresAt) && $now->lt($nextRequestAllowedAt)) {
            $remaining = (int) $now->diffInSeconds($nextRequestAllowedAt);
            Notification::make()
                ->title(__('profile.wait'))
                ->body(__('profile.please_wait', ['seconds' => $remaining]))
                ->warning()
                ->send();
            return;
        }

        // generate and store OTP
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
                ->title(__('profile.success'))
                ->body(__('profile.otp_sent'))
                ->success()
                ->send();
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('profile.sms_error'))
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function submit(): void
    {
        $user = auth()->user();

        // اعتبارسنجی OTP فقط اگر موبایل تأیید نشده باشد
        if (! $user->mobile_verified_at) {
            $sessionOtp = session('otp_code');
            $expiresAt = session('otp_expires_at');

            if (! $sessionOtp || ! $expiresAt || now()->gt($expiresAt)) {
                Notification::make()
                    ->title(__('profile.error'))
                    ->body(__('profile.otp_expired'))
                    ->danger()
                    ->send();
                return;
            }

            if ($this->data['otp_code'] != $sessionOtp) {
                Notification::make()
                    ->title(__('profile.error'))
                    ->body(__('profile.otp_invalid'))
                    ->danger()
                    ->send();
                return;
            }

            // تأیید موبایل
            $user->mobile_verified_at = now();
            $user->save();

            session()->forget(['otp_code', 'otp_expires_at', 'otp_next_request_allowed_at']);

            Notification::make()
                ->title(__('profile.success'))
                ->body(__('profile.mobile_verified'))
                ->success()
                ->send();
        }

        // آپدیت فیلدهای user
        $user->update([
            'first_name' => $this->data['first_name'],
            'last_name'  => $this->data['last_name'],
        ]);

        // لیست مجاز برای relationship و gender
        $allowedRelationships = [
            'مادر', 'پدر', 'برادر', 'خواهر', 'خاله', 'دایی',
            'عمه', 'عمو', 'پدربرزگ', 'مادربزرگ', 'سرپرست', 'سایر',
        ];

        $allowedGenders = ['مرد', 'زن'];

        $relationship = in_array($this->data['relationship'], $allowedRelationships)
            ? $this->data['relationship'] : null;

        $gender = in_array($this->data['gender'], $allowedGenders)
            ? $this->data['gender'] : null;

        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'relationship' => $relationship,
                'province'     => $this->data['province'] ?: null,
                'city'         => $this->data['city'] ?: null,
                'address'      => $this->data['address'] ?: null,
                'gender'       => $gender,
                'postal_code'  => $this->data['postal_code'] ?: null,
            ]
        );

        Notification::make()
            ->title(__('profile.saved'))
            ->success()
            ->send();

        // بروزرسانی وضعیت تأیید موبایل در فرم برای غیرفعال کردن فیلد
        $this->data['mobile_verified'] = true;
    }

}
