<?php

namespace App\Filament\Pages;

use App\Models\Test;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;
use Filament\Actions\Action;

class ViewTest extends Page
{
    protected static ?string $navigationIcon = null;
    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.view-test';
    protected static ?string $slug = 'view-test/{slug}';
    protected static ?string $title = null;
    public ?Test $test = null;

    public function getTitle(): string
    {
        return $this->test?->title ?? __('test.view_test');
    }

    public function mount(?string $slug = null): void
    {
        if ($slug) {
            $this->test = Test::where('slug', $slug)->firstOrFail();
            static::$title = $this->test->title;
        }
    }

    public static function getSlug(): string
    {
        return 'view-test/{test:slug}';
    }

}
