@if ($booking)
    <section class="p-4 sm:px-8 space-y-4">
        <h2 class="mb-6 text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100 flex items-center space-x-2">
            @if (isset($icon) && $icon)
                <x-dynamic-component :component="'icon.' . $icon" style="height: .75lh; width: .75lh" class="fill-current"
                    aria-hidden="true" />
            @endif
            <span>{{ $title }}</span>
        </h2>

        <div class="border divide-y max-w-xl">
            <h3
                class="font-medium px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                <span x-data="{{ Js::from(['start_at' => localDate($booking->start_at)]) }}" x-text="dateString(start_at)"></span>
            </h3>

            <p class="text-left">
                <a href="{{ route($route, $booking) }}"
                    class="block px-3 py-2 hover:bg-opacity-15 hover:bg-gray-900 hover:text-black dark:text-gray-100 dark:hover:bg-opacity-15 dark:hover:bg-white dark:hover:text-white">
                    <span class="mr-4">
                        @if (localDate($booking->start_at)->isSameDay(localDate($booking->end_at)))
                            {{ __(':start_time - :end_time', [
                                'start_time' => localDate($booking->start_at)->format('H:i'),
                                'end_time' => localDate($booking->end_at)->format('H:i'),
                            ]) }}
                        @else
                            {{ __(':start_time', [
                                'start_time' => localDate($booking->start_at)->format('H:i'),
                            ]) }}
                        @endif
                    </span>

                    <span>{{ $booking->activity }} for {{ $booking->group_name }}</span>
                </a>
            </p>
        </div>

        <p><a href="{{ route($more['route']) }}">{{ $more['label'] }}</a></p>
    </section>
@endif
