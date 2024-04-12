<x-layout.app :title="__(':Name - Kit Check', ['name' => $kitCheck->user->name])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10 px-4 sm:px-8">
            <div class="flex items-center justify-between max-w-prose">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">
                    @lang(':Name - Kit Check', ['name' => $kitCheck->user->name])
                </h1>
            </div>
        </header>

        <div class="py-2 px-4 sm:px-8 text-gray-700 dark:text-gray-300">
            <div class="space-y-4 max-w-prose">
                <div class="flex items-center gap-2">
                    <h2 class="text-xl font-medium" x-data="{{ Js::from(['checked_on' => localDate($kitCheck->checked_on)]) }}" x-text="dateString(checked_on)">
                        {{ localDate($kitCheck->checked_on)->toFormattedDayDateString() }}
                    </h2>

                    <x-badge.kit-check-expired :expired="$kitCheck->isExpired()" class="text-md" />
                </div>

                <div>
                    <x-fake-label>@lang('Checked by')</x-fake-label>
                    <p>{{ $kitCheck->checked_by->name }}</p>
                </div>

                @isset($kitCheck->comment)
                    <div>
                        <x-fake-label>@lang('Comment')</x-fake-label>
                        <x-markdown :text="$kitCheck->comment" />
                    </div>
                @endisset
            </div>

            <footer class="mt-6 flex flex-wrap items-center gap-4">
                <x-button.primary :href="route('kit-check.edit', $kitCheck)" :label="__('Edit')" />

                @can('viewAny', App\Models\KitCheck::class)
                    <x-button.secondary :href="route('kit-check.user.index', $kitCheck->user)">
                        @lang('Back')
                    </x-button.secondary>
                @endcan
            </footer>
        </div>
    </section>
</x-layout.app>
