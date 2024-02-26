@props(['booking'])
<div class="space-y-1 my-2 max-w-xl flex-grow">
    <p
        class="text-lg text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200 flex items-center justify-between max-w-xl">
        {{ $booking->location }}
        <x-badge.booking-status :status="$booking->status" class="text-sm" />
    </p>
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('When') }}</dfn>
        @if (localDate($booking->start_at)->isSameDay(localDate($booking->end_at)))
            {{ __(':start_date from :start_time to :end_time', [
                'start_date' => localDate($booking->start_at)->toFormattedDayDateString(),
                'start_time' => localDate($booking->start_at)->format('H:i'),
                'end_time' => localDate($booking->end_at)->format('H:i'),
            ]) }}
        @else
            {{ __(':start to :end', [
                'start' => localDate($booking->start_at)->toDayDateTimeString(),
                'end' => localDate($booking->end_at)->toDayDateTimeString(),
            ]) }}
        @endif
    </p>
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Duration') }}</dfn>
        {{ $booking->start_at->diffAsCarbonInterval($booking->end_at) }}</p>
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Group Name') }}</dfn>
        {{ $booking->group_name }}</p>
    @if (!empty($booking->notes))
        <div><dfn class="not-italic font-bold after:content-[':']">{{ __('Notes') }}</dfn>
            <x-markdown :text="$booking->notes" />
        </div>
    @endif
</div>
