<x-filament::page>
    <div class="flex flex-col md:flex-row gap-6">

        {{-- سایدبار متا --}}
        <aside class="w-full md:w-1/3 md:sticky self-start" style="top: 100px;">
            <div class="rounded-xl bg-white dark:bg-gray-800 p-6 shadow space-y-4">
                {{-- اطلاعات متا --}}
                @if ($test->meta?->purpose)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> هدف آزمون:</strong> {{ $test->meta->purpose }}
                    </div>
                @endif

                @if ($test->meta?->target_age_group)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> گروه سنی هدف:</strong> {{ $test->meta->target_age_group }}
                    </div>
                @endif

                @if ($test->meta?->test_type)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> نوع آزمون:</strong> {{ $test->meta->test_type }}
                    </div>
                @endif

                @if ($test->meta?->approximate_duration)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> مدت زمان تقریبی:</strong> {{ $test->meta->approximate_duration }}
                    </div>
                @endif

                @if ($test->meta?->required_tools)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> ابزارهای مورد نیاز:</strong> {{ $test->meta->required_tools }}
                    </div>
                @endif

                @if ($test->meta?->analysis_method)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> روش تحلیل:</strong> {{ $test->meta->analysis_method }}
                    </div>
                @endif

                @if ($test->meta?->reliability_coefficient)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> ضریب پایایی:</strong> {{ $test->meta->reliability_coefficient }}
                    </div>
                @endif

                @if ($test->meta?->validity)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> روایی:</strong> {{ $test->meta->validity }}
                    </div>
                @endif

                @if ($test->meta?->language_requirement)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> نیازمندی‌های زبانی:</strong> {{ $test->meta->language_requirement }}
                    </div>
                @endif

                @if ($test->meta?->iq_estimation_possibility)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> امکان برآورد IQ:</strong> {{ $test->meta->iq_estimation_possibility }}
                    </div>
                @endif

                @if ($test->meta?->main_applications)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> کاربردهای اصلی:</strong> {{ $test->meta->main_applications }}
                    </div>
                @endif

                @if ($test->meta?->strengths)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> نقاط قوت:</strong> {{ $test->meta->strengths }}
                    </div>
                @endif

                @if ($test->meta?->limitations)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> محدودیت‌ها:</strong> {{ $test->meta->limitations }}
                    </div>
                @endif

                @if ($test->meta?->advanced_versions)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> نسخه‌های پیشرفته:</strong> {{ $test->meta->advanced_versions }}
                    </div>
                @endif

                @if ($test->meta?->advantages_of_execution)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        <strong><x-heroicon-o-ellipsis-vertical class="w-6 h-6 inline"/> مزایای اجرای آزمون در آدمک:</strong> {{ $test->meta->advantages_of_execution }}
                    </div>
                @endif

                {{-- دکمه شرکت در آزمون --}}
                <div class="mt-6">
                    <x-filament::button size="lg" class="w-full">
                        {{ __('test.join_test') }}
                    </x-filament::button>
                </div>

            </div>
        </aside>



        {{-- محتوای اصلی --}}
        <div class="w-full md:w-2/3 flex flex-col items-center space-y-6">
            @if ($test->thumbnail)
                <img src="{{ asset('storage/' . $test->thumbnail) }}"
                     alt="{{ $test->title }}"
                     class="w-full max-w-xl rounded-xl shadow">
            @endif

            <h1 class="text-2xl font-bold text-gray-800 dark:text-white text-center">
                {{ $test->title }}
            </h1>

            @if ($test->description)
                <div class="prose dark:prose-invert max-w-3xl text-justify">
                    {!! $test->description !!}
                </div>
            @endif


        </div>

    </div>
</x-filament::page>
