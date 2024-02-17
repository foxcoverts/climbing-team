@props(['booking'])
<header>
    <h2 class="text-3xl font-medium">
        {{ $booking->activity }} - {{ localDate($booking->start_at)->toFormattedDayDateString() }}
    </h2>
</header>
