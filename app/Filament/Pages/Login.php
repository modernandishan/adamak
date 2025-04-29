<?php
namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Auth\Login as BaseLogin;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Components\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Notifications\Notification;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;

class Login extends BaseLogin
{
    public ?array $data = null;

    public function mount(): void
    {
        parent::mount();

        $this->data = [
            'mobile_number' => '',
            'password' => '',
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getLoginFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getForgetPasswordLinkFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getLoginFormComponent(): Component
    {
        return TextInput::make('mobile_number')
            ->label('شماره موبایل')
            ->tel()
            ->required()
            ->minLength(11)
            ->maxLength(11)
            ->regex('/^09[0-9]{9}$/')
            ->rule('exists:users,mobile');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->password()
            ->label('رمز عبور')
            ->required()
            ->minLength(6);
    }

    protected function getForgetPasswordLinkFormComponent(): Component
    {
        return View::make('forgot-password-link')
            ->view('components.forgot-password-link');
    }

    public function authenticate(): ?LoginResponse
    {
        $this->validate();

        $mobile = $this->data['mobile_number'];
        $password = $this->data['password'];

        $user = User::where('mobile', $mobile)->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            Notification::make()
                ->title('ورود ناموفق')
                ->body('شماره موبایل یا رمز عبور اشتباه است.')
                ->danger()
                ->send();

            $this->addError('mobile_number', 'اطلاعات ورود اشتباه است.');
            return null;
        }

        Auth::login($user);

        Notification::make()
            ->title('خوش آمدید!')
            ->body('شما با موفقیت وارد شدید.')
            ->success()
            ->send();

        return app(LoginResponse::class);
    }
}
