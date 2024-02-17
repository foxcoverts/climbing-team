@use(\App\Helpers\DateHelper)
@props(['route' => 'booking.show'])

<x-admin.link :href="route($route, $booking)">
    {{ localDate($booking->start_at)->format('H:i') }} - {{ $booking->group_name }}
</x-admin.link>
