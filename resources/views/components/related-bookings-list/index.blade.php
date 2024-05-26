<aside {{ $attributes }}>
    <h2 class="text-xl font-medium border-b border-gray-800 dark:border-gray-200">
        {{ __('Related Bookings') }}
    </h2>

    <ul class="my-2 space-y-1 list-disc ml-5">
        @foreach ($bookings as $related)
            <li><a href="{{ route('booking.show', $related) }}">
                    {{ $related->activity }}
                    -
                    <span x-data="{{ Js::from(['start_at' => localDate($related->start_at, $related->timezone)]) }}" x-text="dateString(start_at)">&nbsp;</span>
                </a></li>
        @endforeach
    </ul>
</aside>
