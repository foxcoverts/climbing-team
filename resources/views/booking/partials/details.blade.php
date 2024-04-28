@props(['booking'])
@php($localStartAt = localDate($booking->start_at, $booking->timezone))
@php($localEndAt = localDate($booking->end_at, $booking->timezone))
<div class="space-y-2">
    <div class="border-b border-gray-800 dark:border-gray-200">
        <h2 class="text-xl font-medium text-gray-800 dark:text-gray-200">@lang('Booking Details')</h2>
    </div>

    <div>
        <x-fake-label :value="__('When')" />
        <p class="text-gray-700 dark:text-gray-300">
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

    @if (!empty($booking->lead_instructor_notes))
        @can('lead', $booking)
            <div class="prose dark:prose-invert prose-p:my-2 prose-ul:my-2 prose-ol:my-2 prose-li:my-0">
                <x-fake-label :value="__('Lead Instructor Notes')" />
                <x-markdown :text="$booking->lead_instructor_notes" />
            </div>
        @endcan
    @endif
</div>
