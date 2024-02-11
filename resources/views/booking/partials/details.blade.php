@props(['booking'])

<header class="border-b border-gray-800 dark:border-gray-200">
    <h2 class="text-3xl font-medium">
        {{ $booking->activity }} - {{ $booking->start_at->format('D j M') }}
    </h2>
    <p class="text-lg text-gray-800 dark:text-gray-200">{{ $booking->location }}</p>
</header>

<div class="space-y-1 my-2">
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('When') }}</dfn>
        @if ($booking->start_at->isSameDay($booking->end_at))
            {{ __(':start_date from :start_time to :end_time', [
                'start_date' => $booking->start_at->toFormattedDayDateString(),
                'start_time' => $booking->start_at->format('H:i'),
                'end_time' => $booking->end_at->format('H:i'),
            ]) }}
        @else
            {{ __(':start to :end', [
                'start' => $booking->start_at->toDayDateTimeString(),
                'end' => $booking->end_at->toDayDateTimeString(),
            ]) }}
        @endif
    </p>
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Duration') }}</dfn>
        {{ $booking->start_at->diffAsCarbonInterval($booking->end_at) }}</p>
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Status') }}</dfn>
        {{ __($booking->status->name) }}</p>
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Location') }}</dfn>
        {{ $booking->location }}</p>
    <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Group Name') }}</dfn>
        {{ $booking->group_name }}</p>
    @if (!empty($booking->notes))
        <div><dfn class="not-italic font-bold after:content-[':']">{{ __('Notes') }}</dfn>
            <x-markdown :text="$booking->notes" />
        </div>
    @endif
</div>
