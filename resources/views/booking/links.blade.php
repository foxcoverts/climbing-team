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
                    <span class="grow">{{ __('Calendar Links') }}</span>
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8 border-b">
            <div class="max-w-prose space-y-2">
                <p>{{ __('Use these links to subscribe to your bookings from other applications.') }}</p>
                <p class="text-sm">
                    {{ __('Warning: These links are unique to your account, do not share them with other people.') }}
                </p>
            </div>
        </div>

        <div class="p-4 sm:px-8 border-b">
            <div class="max-w-prose space-y-2">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">{{ __('My Rota') }}</h2>
                <x-input-label :value="__('Rota Link')" for="rota-link" />
                <x-password-input id="rota-link" name="rota-link" x-model="links.rota" :copy="true" readonly
                    class="w-full" />
            </div>
        </div>

        <div class="p-4 sm:px-8 border-b">
            <div class="max-w-prose space-y-2">
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">{{ __('Calendar') }}</h2>
                <x-input-label :value="__('Calendar Link')" for="calendar-link" />
                <x-password-input id="calendar-link" name="calendar-link" x-model="links.calendar" :copy="true"
                    readonly class="w-full" />
            </div>
        </div>

        <div class="p-4 sm:px-8 border-b">
            <form action="{{ route('booking.links.reset') }}" method="POST" x-data="{ submitted: false }"
                class="max-w-prose space-y-2" x-on:submit="setTimeout(() => submitted = true, 0)">
                @csrf @method('DELETE')
                <h2 class="text-xl font-medium text-gray-900 dark:text-gray-100">{{ __('Reset') }}</h2>
                <p>{{ __('You can reset these links and make the current ones invalid.') }}</p>
                <x-button.danger x-bind:disabled="submitted"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Reset Links') }}'" />
            </form>
        </div>
    </section>
</x-layout.app>
