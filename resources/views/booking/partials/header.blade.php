@props(['booking'])
<header>
    <h2 class="text-3xl font-medium">
        {{ $booking->activity }} - {{ $booking->start_at->format('D j M') }}
    </h2>
</header>
