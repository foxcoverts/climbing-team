@props(['booking'])
<header>
    <h2 class="text-3xl font-medium text-gray-900 dark:text-gray-100 flex flex-wrap gap-2">
        <span>{{ $booking->activity }}</span>
        -
        <span x-data='{ start_at:"{{ $booking->start_at }}" }' x-text="dateString(start_at)"></span>
    </h2>
</header>
