@use('App\Enums\BookingStatus')
@props([
    'status',
    'color' => match ($status) {
        BookingStatus::Tentative => 'yellow',
        BookingStatus::Confirmed => 'lime',
        default => 'pink',
    },
    'icon' => match ($status) {
        BookingStatus::Tentative => 'calendar.tee',
        BookingStatus::Confirmed => 'calendar.check',
        default => 'calendar.cross',
    },
    'label' => __('app.booking.status.' . $status->value),
])
<x-badge :$color :$icon :$label {{ $attributes }} />
