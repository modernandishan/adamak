<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center">
            <a href="{{ route('filament.admin.pages.wallet-charge') }}" class="relative inline-flex items-center justify-center text-gray-500 hover:text-primary-600">
                <x-heroicon-o-banknotes class="w-6 h-6" />
                <span class="absolute -top-2 -right-2 bg-primary-600 text-white rounded-full text-xs px-1">
            {{ number_format(auth()->user()->wallet->balance ?? 0) }}
        </span>
            </a>
        </div>

    </x-filament::section>
</x-filament-widgets::widget>
