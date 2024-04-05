<x-layout.app :title="__('Keys')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-50">
            <div class="px-4 sm:px-8 flex items-center justify-between">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">@lang('Keys')</h1>

                @can('create', App\Models\Key::class)
                    <nav>
                        <x-button.primary :href="route('key.create')">@lang('Add Key')</x-button.primary>
                    </nav>
                @endcan
            </div>
        </header>

        @foreach ($keys as $key)
            <div class="py-2 px-4 sm:px-8 border-b">
                <h2 class="text-lg font-medium">{{ $key->name }}</h2>
                <p class="text-gray-900 dark:text-gray-100"><dfn
                        class="not-italic font-medium text-black dark:text-white">@lang('Held by'):</dfn>
                    {{ $key->holder->name }}
                </p>
            </div>
        @endforeach
    </section>
</x-layout.app>
