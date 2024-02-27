<aside class="my-2 flex-grow max-w-xl">
    <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200">
        {{ __('Guest list') }}
    </h2>

    @if ($lead_instructor = $booking->attendees()->find($booking->lead_instructor))
        <h3 class="text-lg">{{ __('Lead Instructor') }}</h3>
        <p class="mb-3 flex space-x-1 items-center">
            @include('booking.partials.guest-list.item', [
                'booking' => $booking,
                'attendee' => $lead_instructor,
            ])
        </p>
    @endif

    @foreach ($attendees->groupBy('attendance.status') as $status => $attendees)
        <h3 class="text-lg">{{ __("app.attendee.status.$status") }}</h3>
        <ul class="mb-3 space-y-1">
            @foreach ($attendees as $attendee)
                <li class="flex space-x-1 items-center">
                    @include('booking.partials.guest-list.item', [
                        'booking' => $booking,
                        'attendee' => $attendee,
                    ])
                </li>
            @endforeach
        </ul>
    @endforeach
</aside>
