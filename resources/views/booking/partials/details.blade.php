@props(['booking'])
<div class="space-y-2 w-full max-w-prose flex-grow">
    <div class="border-b border-gray-800 dark:border-gray-200">
        <h2 class="text-xl font-medium text-gray-800 dark:text-gray-200">@lang('Booking Details')</h2>
    </div>

    <div>
        <x-fake-label :value="__('When')" />
        <p class="text-gray-700 dark:text-gray-300">
            <span x-data="{{ Js::from(['start_at' => localDate($booking->start_at)]) }}"
                x-text="dateString(start_at)">{{ localDate($booking->start_at)->toFormattedDayDateString() }}</span>
            {{ __('from :start_time to :end_time (:duration)', [
                'start_time' => localDate($booking->start_at)->format('H:i'),
                'end_time' => localDate($booking->end_at)->format('H:i'),
                'duration' => $booking->start_at->diffAsCarbonInterval($booking->end_at),
            ]) }}
        </p>
    </div>

    <div>
        <x-fake-label :value="__('Location')" />
        <p class="text-gray-700 dark:text-gray-300">{{ $booking->location }}</p>
    </div>

    <div>
        <x-fake-label :value="__('Activity')" />
        <p class="text-gray-700 dark:text-gray-300">{{ $booking->activity }}</p>
    </div>

    <div>
        <x-fake-label :value="__('Group')" />
        <p class="text-gray-700 dark:text-gray-300">{{ $booking->group_name }}</p>
    </div>

    @if (!empty($booking->notes))
        <div class="prose dark:prose-invert prose-p:my-2 prose-ul:my-2 prose-ol:my-2 prose-li:my-0">
            <x-fake-label :value="__('Notes')" />
            <x-markdown :text="$booking->notes" />
        </div>
    @endif
</div>
