@props(['booking'])
<header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
    <div class="px-4 sm:px-8">
        <div class="max-w-prose flex flex-wrap py-2 min-h-16 items-center justify-between gap-2">
            <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                <span>{{ $booking->activity }}</span>
                -
                <span x-data='{ start_at:"{{ localDate($booking->start_at) }}" }' x-text="dateString(start_at)"></span>
            </h1>
            <div class="grow flex justify-end">
                <x-badge.booking-status :status="$booking->status" />
            </div>
        </div>
    </div>
</header>
