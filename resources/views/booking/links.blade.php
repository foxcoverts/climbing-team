<x-layout.app :title="__('Calendar Links')">
    <section x-data="{
        links: {{ Js::from([
            'calendar' => route('booking.ics', $currentUser),
            'rota' => route('booking.rota.ics', $currentUser),
        ]) }},
    }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.calendar.download style="height: .75lh" class="fill-current" aria-hidden="true" />
                    <span class="grow">@lang('Calendar Links')</span>
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8 border-b">
            <div class="max-w-prose space-y-2">
                <p>@lang('Use these links to subscribe to your bookings from other applications.')</p>
                <p class="text-sm">@lang('Warning: These links are unique to your account, do not share them with other people.')</p>
            </div>
        </div>

        <div class="p-4 sm:px-8 border-b">
            <div class="max-w-prose space-y-2">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">@lang('My Rota')</h2>
                <x-input-label :value="__('Rota Link')" for="rota-link" />
                <x-password-input id="rota-link" name="rota-link" x-model="links.rota" :copy="true" readonly
                    class="w-full" />
            </div>
        </div>

        <div class="p-4 sm:px-8 border-b">
            <div class="max-w-prose space-y-2">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">@lang('Calendar')</h2>
                <x-input-label :value="__('Calendar Link')" for="calendar-link" />
                <x-password-input id="calendar-link" name="calendar-link" x-model="links.calendar" :copy="true"
                    readonly class="w-full" />
            </div>
        </div>

        {{-- TODO -------
        <div class="p-4 sm:px-8 border-b">
            <form action="" method="POST" x-data="{ submitted: false }" class="max-w-prose space-y-2"
                x-on:submit="setTimeout(() => submitted = true, 0)">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">@lang('Reset')</h2>
                <p>@lang('You can reset these links and make the current ones invalid.')</p>
                <x-button.primary :label="__('Reset')" />
            </form>
        </div>
        --}}
    </section>
</x-layout.app>
