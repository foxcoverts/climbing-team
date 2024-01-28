<x-admin.link :href="route('booking.show', $booking)">
    {{ $booking->start_at }} - {{ $booking->group_name }}
</x-admin.link>
