@use('App\Enums\BookingStatus')
@props(['status'])

@switch($status)
    @case(BookingStatus::Tentative)
        @php($icon = 'calendar.tee')
        @php($color = 'yellow')
    @break

    @case(BookingStatus::Confirmed)
        @php($icon = 'calendar.check')
        @php($color = 'lime')
    @break

    @default
        @php($icon = 'calendar.cross')
        @php($color = 'pink')
@endswitch

<x-badge
    {{ $attributes->merge([
        'color' => $color,
        'icon' => $icon,
        'label' => __('app.booking.status.' . $status->value),
    ]) }} />
