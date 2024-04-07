<x-layout.app>
    <x-slot:title>
        {{ $booking->activity }} on {{ localDate($booking->start_at)->toFormattedDayDateString() }}
    </x-slot:title>

    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8 flex flex-wrap gap-4">
            <div class="w-full max-w-prose">
                @include('booking.partials.details')

                @can('update', $booking)
                    <footer class="flex flex-wrap items-start gap-4 mt-4">
                        <x-button.primary :href="route('booking.edit', $booking)" :label="__('Edit')" />
                        <x-button.secondary :href="route('booking.share', $booking)" :label="__('Share')" />
                    </footer>
                @endcan

                @include('booking.partials.recent-activity')
            </div>

            <x-guest-list :$booking :$currentUser />
        </div>
    </section>
</x-layout.app>
