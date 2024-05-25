<x-layout.app>
    <x-slot:title>
        {{ __('Related Bookings') }} -
        {{ $booking->activity }} on
        {{ localDate($booking->start_at, $booking->timezone)->toFormattedDayDateString() }}
    </x-slot:title>

    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8">
            <div>
                <h2 class="text-xl font-medium mb-4">{{ __('Related Bookings') }}</h2>

                @include('booking.partials.list', [
                    'related' => $booking,
                    'bookings' => $related,
                    'user' => $currentUser,
                    'toolsView' => 'booking.related.partials.item-tools',
                ])

                <div class="mt-4 flex gap-4">
                    @can('create', [App\Models\Bookable::class, $booking])
                        <x-button.primary :href="route('booking.related.create', $booking)">{{ __('Add Related Booking') }}</x-button.primary>
                    @endcan
                    <x-button.secondary :href="route('booking.show', $booking)">{{ __('Back') }}</x-button.secondary>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
