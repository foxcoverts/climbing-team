<x-layout.app :title="__('Latest Kit Checks')">
    <section x-data="{ showExpired: true }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Latest Kit Checks') }}
                </h1>

                @can('create', App\Models\KitCheck::class)
                    <nav class="flex items-center gap-4 justify-end grow">
                        <x-button.primary :href="route('kit-check.create')" :label="__('Log Kit Check')" />
                    </nav>
                @endcan
            </div>
        </header>

        <div class="text-gray-700 dark:text-gray-300">
            @forelse ($users as $user)
                @isset($user->latestKitCheck)
                    <div class="py-2 px-4 sm:px-8 hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 border-b cursor-pointer"
                        x-data="{{ Js::from(['isExpired' => $user->latestKitCheck->isExpired()]) }}" {{ $user->latestKitCheck->isExpired() ? 'x-cloak' : '' }}
                        x-show="!isExpired || showExpired" x-transition
                        @click="window.location={{ Js::from(route('kit-check.user.index', $user)) }}">
                        <h2 class="text-xl font-medium"><a
                                href="{{ route('kit-check.user.index', $user) }}">{{ $user->name }}</a></h2>

                        <div class="flex items-center gap-2">
                            <p>
                                <span x-data="{{ Js::from(['checked_on' => localDate($user->latestKitCheck->checked_on)]) }}" x-text="dateString(checked_on)">
                                    {{ localDate($user->latestKitCheck->checked_on)->toFormattedDayDateString() }}
                                </span>
                            </p>
                            <x-badge.kit-check-expired :expired="$user->latestKitCheck->isExpired()" class="text-xs" />
                        </div>
                    </div>
                @else
                    <div class="py-2 px-4 sm:px-8" x-cloak x-show="showExpired" x-transition>
                        <h2 class="text-xl font-medium">{{ $user->name }}</h2>
                        <p>{{ __('This user has not checked their kit yet.') }}</p>
                    </div>
                @endisset
            @empty
                <p class="p-4 sm:px-8">{{ __('No kit has been checked yet.') }}
            @endforelse
        </div>
    </section>
</x-layout.app>
