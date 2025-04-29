<x-filament::page>
    {{ $this->form }}

    <div class="mt-6 flex justify-end">
        <x-filament::button
            wire:click="submit"
            type="button"
            icon="heroicon-o-check"
        >
            ذخیره تغییرات
        </x-filament::button>
    </div>
</x-filament::page>
