@props(['booking'])
<header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
    <div class="py-2 min-h-16 flex flex-wrap items-center gap-2 max-w-prose">
        <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
            {{ $booking->activity }}
            -
            <span x-data="{{ Js::from(['start_at' => localDate($booking->start_at)]) }}" x-text="dateString(start_at)">&nbsp;</span>
        </h1>
        <div>
            <x-badge.booking-status :status="$booking->status" />
        </div>
    </div>
</header>
