<x-layout.app :title="__('Deleted Bookings')">
    <section class="sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-3xl font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Deleted Bookings') }}
                    </h2>
                </header>

                <div>
                    @forelse ($bookings as $day => $bookings)
                        <h3 class="text-xl font-normal mt-3 text-gray-900 dark:text-gray-100">
                            {{ localDate($day)->toFormattedDayDateString() }}
                        </h3>
                        <div class="grid grid-cols-1 divide-y divide-gray-200 border-y border-gray-200 my-2">
                            @forelse ($bookings as $booking)
                                @include('booking.partials.item', [
                                    'booking' => $booking,
                                    'route' => 'trash.booking.show',
                                ])
                            @empty
                                @include('booking.partials.empty')
                            @endforelse
                        </div>
                    @empty
                        @include('booking.partials.empty')
                    @endforelse
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
