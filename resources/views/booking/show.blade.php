<x-layout.app>
    <x-slot:title>
        {{ $booking->activity }} on {{ localDate($booking->start_at)->toFormattedDayDateString() }}
    </x-slot:title>

    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8 grid md:max-lg:grid-cols-booking xl:grid-cols-booking gap-4">
            <div>
                @include('booking.partials.details')

                @can('update', $booking)
                    <footer class="flex flex-wrap items-start gap-4 mt-4 w-full max-w-prose">
                        <x-button.primary :href="route('booking.edit', $booking)" :label="__('Edit')" />
                        <x-button.secondary :href="route('booking.share', $booking)" :label="__('Share')" />
                    </footer>
                @endcan
            </div>

            <x-guest-list :$booking :$currentUser class="row-span-2" />

            @include('booking.partials.recent-activity')
        </div>
    </section>
</x-layout.app>
