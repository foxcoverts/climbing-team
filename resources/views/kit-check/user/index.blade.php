<x-layout.app :title="__(':Name - Kit Checks', ['name' => $user->name])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10 px-4 sm:px-8">
            <div class="flex items-center justify-between max-w-prose">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">
                    @lang(':Name - Kit Checks', ['name' => $user->name])
                </h1>

                @can('create', App\Models\KitCheck::class)
                    <nav>
                        <x-button.primary :href="route('kit-check.create', ['users' => $user->id])">@lang('Log Kit Check')</x-button.primary>
                    </nav>
                @endcan
            </div>
        </header>

        <div class="text-gray-700 dark:text-gray-300">
            @forelse ($kitChecks as $kitCheck)
                <div class="py-2 px-4 sm:px-8 border-b">
                    <div class="max-w-prose">
                        <div class="flex items-center gap-2">
                            <h2 @class(['text-lg font-medium']) x-data="{{ Js::from(['checked_on' => localDate($kitCheck->checked_on)]) }}" x-text="dateString(checked_on)">
                                {{ localDate($kitCheck->checked_on)->toFormattedDayDateString() }}
                            </h2>

                            <x-badge.kit-check-expired :expired="$kitCheck->isExpired()" class="text-sm" />
                        </div>

                        <div class="mt-2">
                            <x-fake-label>@lang('Checked by')</x-fake-label>
                            <p>{{ $kitCheck->checked_by->name }}</p>
                        </div>

                        @isset($kitCheck->comment)
                            <div class="mt-2">
                                <x-fake-label>@lang('Comment')</x-fake-label>
                                <x-markdown :text="$kitCheck->comment" />
                            </div>
                        @endisset

                        @can('update', $kitCheck)
                            <div class="mt-4">
                                <x-button.primary :href="route('kit-check.edit', $kitCheck)" :label="__('Edit')" />
                            </div>
                        @endcan
                    </div>
                </div>
            @empty
                <p class="p-4 sm:px-8">@lang('This user has not checked their kit yet.')</p>
            @endforelse
        </div>

        @can('viewAny', App\Models\KitCheck::class)
            <footer class="p-4 sm:px-8">
                <x-button.secondary :href="route('kit-check.index')" :label="__('Back')" />
            </footer>
        @endcan
    </section>
</x-layout.app>
