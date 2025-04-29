<?php

namespace App\Filament\Pages;

use Filament\Pages\Auth\Register as BaseRegister;
use App\Models\User;
use App\Models\Profile;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Component;

class Register extends BaseRegister
{
    public $first_name;
    public $last_name;
    public $mobile;
    public $password;

    public function mount(): void
    {
        parent::mount();
        $this->first_name = '';
        $this->last_name = '';
        $this->mobile = '';
        $this->password = '';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getNameFormComponent(),
                $this->getLastNameFormComponent(),
                $this->getMobileFormComponent(),
                $this->getPasswordFormComponent(),
            ])
            ->statePath('data');
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('first_name')
            ->label('نام')
            ->required();
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('last_name')
            ->label('نام خانوادگی')
            ->required();
    }

    protected function getMobileFormComponent(): Component
    {
        return TextInput::make('mobile')
            ->label('شماره موبایل')
            ->tel()
            ->required()
            ->minLength(11)
            ->maxLength(11)
            ->regex('/^09[0-9]{9}$/')
            ->rule('unique:users,mobile');
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->password()
            ->label('رمز عبور')
            ->required()
            ->minLength(6);
    }

    public function submit(): void
    {
        $this->validate();

        // ثبت نام کاربر جدید
        $user = User::create([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'mobile' => $this->mobile,
            'password' => Hash::make($this->password),
        ]);

        // ایجاد پروفایل کاربر
        Profile::create([
            'user_id' => $user->id,
            'relationship' => 'پدر', // یا هر مقداری که بخواهید
            'province' => 'استان دلخواه',
            'city' => 'شهر دلخواه',
            'address' => 'آدرس دلخواه',
            'gender' => 'مرد', // یا زن
            'postal_code' => 'کد پستی دلخواه',
        ]);

        // ورود به سیستم
        auth()->login($user);

    }
}

