<?php

namespace App\Filament\Pages;

use App\Models\Test;
use Filament\Pages\Page;

class ListAvailableTests extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string $view = 'filament.pages.list-available-tests';
    public static function getNavigationLabel(): string
    {
        return __('test.available_tests');
    }
    public $tests;

    public function mount(): void
    {
        $this->tests = Test::where('status', true)->latest()->get();
    }
    public function getTitle(): string
    {
        return __('test.available_tests');
    }

}
