<x-admin.link :href="route('booking.show', $booking)">
    {{ $booking->start_at }} - {{ $booking->activity }} [{{ __($booking->status->name) }}]
</x-admin.link>
