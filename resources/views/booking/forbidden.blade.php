<x-layout.app>
    <x-slot:title>
        {{ $booking->activity }} on {{ localDate($booking->start_at)->toFormattedDayDateString() }}
    </x-slot:title>

    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            <div class="w-full max-w-xl">
                <div class="space-y-2 my-2 w-full max-w-xl flex-grow">
                    <div
                        class="text-lg text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200 flex items-center justify-between">
                        <p class="flex items-center">
                            <x-icon.location class="h-5 w-5 fill-current mr-1" />
                            {{ $booking->location }}
                        </p>
                    </div>

                    <div>
                        <x-fake-label :value="__('When')" />
                        <p>
                            @if (localDate($booking->start_at)->isSameDay(localDate($booking->end_at)))
                                {{ __(':start_date from :start_time to :end_time', [
                                    'start_time' => localDate($booking->start_at)->format('H:i'),
                                    'start_date' => localDate($booking->start_at)->toFormattedDayDateString(),
                                    'end_time' => localDate($booking->end_at)->format('H:i'),
                                ]) }}
                            @else
                                {{ __(':start to :end', [
                                    'start' => localDate($booking->start_at)->toDayDateTimeString(),
                                    'end' => localDate($booking->end_at)->toDayDateTimeString(),
                                ]) }}
                            @endif
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
                        <h2 class="text-lg text-center">@lang('Forbidden')</h2>

                        <p class="text-center">
                            @lang('Sorry, you have not been invited to this booking.')
                        </p>

                        <p class="text-center text-sm">
                            @lang('Please contact the Team Leader if you believe this is an error.')
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
