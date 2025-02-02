<x-layout.app>
    <x-slot:title>
        {{ $booking->activity }} on {{ localDate($booking->start_at, $booking->timezone)->toFormattedDayDateString() }}
    </x-slot:title>

    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8 grid md:max-lg:grid-cols-booking xl:grid-cols-booking gap-4">
            <div>
                @include('booking.partials.details')

                @can('update', $booking)
                    <footer class="flex flex-wrap items-start gap-4 mt-4 w-full max-w-prose">
                        <x-button.secondary :href="route('booking.related.index', $booking)" :label="__('Related')" />
                        <x-button.secondary :href="route('booking.share', $booking)" :label="__('Share')" />
                    </footer>
                @endcan
            </div>

            <div class="row-span-2 flex flex-col gap-4">
                <x-guest-list :$booking :$currentUser />

                <x-related-bookings-list :$booking :$currentUser />
            </div>
        </div>
    </section>
</x-layout.app>
