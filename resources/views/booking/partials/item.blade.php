@props(['route' => 'booking.show'])

<x-admin.link :href="route($route, $booking)">
    {{ $booking->start_at }} - {{ $booking->group_name }}
</x-admin.link>
