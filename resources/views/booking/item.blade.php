<x-admin.link :href="route('booking.show', $booking)">
    {{ $booking->start_at }} - {{ $booking->group_name }} [{{ __($booking->status->name) }}]
</x-admin.link>
