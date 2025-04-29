<x-filament-panels::page>
    <div class="container mx-auto my-5">
        <h1 class="text-2xl font-bold text-center mb-4">صفحه شارژ کیف پول</h1>

        <!-- فرم شارژ کیف پول -->
        <div class="bg-gray-100 p-4 rounded">
            <h2 class="text-lg font-semibold mb-2">مبلغ شارژ</h2>

            <form method="POST" action="{{ route('send.to.gateway') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">مبلغ (تومان)</label>
                    <input type="number" name="amount" min="1000" class="form-input mt-1 block w-full" placeholder="مثلاً 50000" required>
                </div>

                <div>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">پرداخت</button>
                </div>
            </form>

        </div>
    </div>
</x-filament-panels::page>
