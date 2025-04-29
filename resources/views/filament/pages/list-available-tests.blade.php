<x-filament::page>
    @if ($this->tests->isEmpty())
        <div class="text-center text-gray-500 dark:text-gray-400 text-lg py-12">
            {{ __('test.no_tests') }}
        </div>
    @else
        <div class="flex flex-wrap justify-start gap-6">
            @foreach ($this->tests as $test)
                <div class="w-full sm:w-[47%] lg:w-[30%] max-w-sm">
                    <div
                        class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-md transition-all duration-300 hover:shadow-xl hover:scale-105 <!--cursor-pointer--> overflow-hidden">

                        @if ($test->thumbnail)
                            <img src="{{ asset('storage/' . $test->thumbnail) }}"
                                 alt="{{ $test->title }}"
                                 class="w-full h-40 object-cover">
                        @else
                            <div class="w-full h-40 bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-gray-400 dark:text-gray-500 text-sm">
                                {{ __('test.no_thumbnail') }}
                            </div>
                        @endif

                        <div class="p-4 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-4">
                                {{ $test->title }}
                            </h3>
                            <div class="mt-auto text-left">
                                <a href="{{url('adamak/view-test/'.$test->slug)}}"
                                   class="inline-block px-4 py-2 text-sm font-medium text-white bg-primary-600 dark:bg-primary-500 rounded-lg hover:bg-primary-700 dark:hover:bg-primary-600 transition-colors duration-200">
                                    {{ __('test.view_test') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament::page>
