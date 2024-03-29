@props(['booking'])
<div class="space-y-2 my-2 w-full max-w-xl flex-grow">
    <div
        class="text-lg text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200 flex items-center justify-between">
        <p class="flex items-center">
            <x-icon.location class="h-5 w-5 fill-current mr-1" />
            {{ $booking->location }}
        </p>
        <x-badge.booking-status :status="$booking->status" class="text-sm" />
    </div>

    <div>
        <x-fake-label :value="__('When')" />
        <p class="text-gray-700 dark:text-gray-300">
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
            ({{ $booking->start_at->diffAsCarbonInterval($booking->end_at) }})
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
