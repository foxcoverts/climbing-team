@php($localStartAt = localDate($booking->start_at, $booking->timezone))
@php($localEndAt = localDate($booking->end_at, $booking->timezone))
<x-layout.guest :updated="$booking->updated_at">
    <x-slot:title>
        {{ $booking->activity }} on {{ $localStartAt->toFormattedDayDateString() }}
    </x-slot:title>
    <x-slot:description>
        {{ __(':activity from :start_time to :end_time at :location.', [
            'activity' => $booking->activity,
            'start_time' => $localStartAt->format('H:i'),
            'end_time' => $localEndAt->format('H:i'),
            'location' => $booking->location,
        ]) }}
    </x-slot:description>
    <x-slot:image width="700" height="700">
        {{ asset('images/dates/' . $booking->start_at->format('n/n-j') . '.png') }}
    </x-slot:image>

    <div class="space-y-2">
        <div>
            <x-fake-label :value="__('When')" />
            <p>
                {{ __(':start_date from :start_time to :end_time', [
                    'start_date' => $localStartAt->toFormattedDayDateString(),
                    'start_time' => $localStartAt->format('H:i'),
                    'end_time' => $localEndAt->format('H:i'),
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
    </div>

    @if ($responded)
        <div
            class="my-4 space-y-4 p-4 border text-black bg-blue-100 border-slate-400 dark:text-white dark:bg-blue-900 dark:border-slate-600">
            <div class="flex justify-center gap-4">
                <x-button.primary :href="route('login')" :label="__('View full details')" />
            </div>

            <p class="text-sm text-center">{{ __('Please login to view full details or change your response.') }}</p>
        </div>
    @else
        <div
            class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
            <p class="text-lg text-center">{{ __('Can you attend this event?') }}</p>

            <div class="flex justify-center gap-4">
                <x-button.primary :href="route('login')" :label="__('View full details')" />
            </div>

            <p class="text-sm text-center">{{ __('Please login to view full details and to RSVP.') }}</p>
        </div>
    @endif
</x-layout.guest>
