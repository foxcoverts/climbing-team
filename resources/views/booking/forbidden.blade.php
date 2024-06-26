@php($localStartAt = localDate($booking->start_at, $booking->timezone))
@php($localEndAt = localDate($booking->end_at, $booking->timezone))
<x-layout.app>
    <x-slot:title>
        {{ $booking->activity }} on {{ $localStartAt->toFormattedDayDateString() }}
    </x-slot:title>

    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8">
            <div class="max-w-prose space-y-2 my-2">
                <div
                    class="text-lg font-medium text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200 flex items-center justify-between">
                    <h2 class="flex items-center">
                        {{ __('Booking Details') }}
                    </h2>
                </div>

                <div>
                    <x-fake-label :value="__('When')" />
                    <p>
                        <span x-data="{{ Js::from(['start_at' => $localStartAt]) }}"
                            x-text="dateString(start_at)">{{ $localStartAt->toFormattedDayDateString() }}</span>
                        {{ __('from :start_time to :end_time (:duration)', [
                            'start_time' => $localStartAt->format('H:i'),
                            'end_time' => $localEndAt->format('H:i'),
                            'duration' => $booking->start_at->diffAsCarbonInterval($booking->end_at),
                        ]) }}
                    </p>
                </div>

                <div>
                    <x-fake-label :value="__('Location')" />
                    <p>{{ $booking->location }}</p>
                </div>

                <div>
                    <x-fake-label :value="__('Activity')" />
                    <p>{{ $booking->activity }}</p>
                </div>

                <div
                    class="my-4 space-y-4 p-4 border text-black bg-red-100 border-red-400 dark:text-white dark:bg-red-900 dark:border-red-600">
                    <h2 class="text-lg text-center">{{ __('Forbidden') }}</h2>

                    <p class="text-center">
                        {{ __('You have not been invited to this booking.') }}
                    </p>

                    <p class="text-center text-sm">
                        {{ __('Please contact the Team Leader if you believe this is an error.') }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
