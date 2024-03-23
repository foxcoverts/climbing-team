<x-layout.guest :updated="$booking->updated_at">
    <x-slot:title>
        {{ $booking->activity }} on {{ localDate($booking->start_at)->toFormattedDayDateString() }}
    </x-slot:title>
    <x-slot:description>
        @if (localDate($booking->start_at)->isSameDay(localDate($booking->end_at)))
            {{ __(':activity on :start_date from :start_time to :end_time at :location.', [
                'activity' => $booking->activity,
                'start_time' => localDate($booking->start_at)->format('H:i'),
                'start_date' => localDate($booking->start_at)->toFormattedDayDateString(),
                'end_time' => localDate($booking->end_at)->format('H:i'),
                'location' => $booking->location,
            ]) }}
        @else
            {{ __(':activity on :start to :end at :location.', [
                'activity' => $booking->activity,
                'start' => localDate($booking->start_at)->toDayDateTimeString(),
                'end' => localDate($booking->end_at)->toDayDateTimeString(),
                'location' => $booking->location,
            ]) }}
        @endif
    </x-slot:description>
    <x-slot:image width="512" height="512">
        {{ asset('images/dates/' . $booking->start_at->format('n/j/n-j-N') . '.png') }}
    </x-slot:image>

    <div class="space-y-2">
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
    </div>

    <div
        class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
        <p class="text-lg text-center">@lang('Can you attend this event?')</p>

        <div class="flex justify-center gap-4">
            <x-button.primary :href="route('booking.show', $booking)">
                @lang('View full details')
            </x-button.primary>
        </div>

        <p class="text-sm text-center">@lang('Please login to view full details and to RSVP.')</p>
    </div>
</x-layout.guest>
