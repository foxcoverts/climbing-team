@use(App\Enums\BookingPeriod)
@use(App\Enums\BookingStatus)
<x-layout.app :title="__('Bookings')">
    <section class="p-4 sm:p-8">
        <header>
            <h2 class="text-3xl font-medium text-gray-900 dark:text-gray-100">
                {{ __('Bookings') }}
            </h2>
        </header>

        <div>
            @forelse ($bookings as $day => $bookings)
                <h3 class="text-xl font-normal mt-3 text-gray-900 dark:text-gray-100">
                    {{ localDate($day)->toFormattedDayDateString() }}
                </h3>
                <div class="grid grid-cols-1 divide-y divide-gray-200 border-y border-gray-200 my-2">
                    @each('booking.partials.item', $bookings, 'booking')
                </div>
            @empty
                @include('booking.partials.empty')
            @endforelse
        </div>

        <x-admin.footer>
            <x-button.primary :href="route('booking.create')">
                {{ __('Add Booking') }}
            </x-button.primary>

            @switch($period)
                @case(BookingPeriod::Past)
                    <x-button.secondary :href="route('booking.index')">
                        {{ __('Future Bookings') }}
                    </x-button.secondary>
                @break

                @case(BookingPeriod::Future)
                    <x-button.secondary :href="route('booking.period', [BookingPeriod::Past, 'status' => 'all'])">
                        {{ __('Past Bookings') }}
                    </x-button.secondary>
                    @if ($status->contains(BookingStatus::Cancelled))
                        <x-button.secondary :href="route('booking.index')">
                            {{ __('Hide Cancelled Bookings') }}
                        </x-button.secondary>
                    @else
                        <x-button.secondary :href="route('booking.index', ['status' => 'all'])">
                            {{ __('Show Cancelled Bookings') }}
                        </x-button.secondary>
                    @endif
                @break

            @endswitch
        </x-admin.footer>
    </section>
</x-layout.app>
