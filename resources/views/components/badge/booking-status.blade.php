@use('App\Enums\BookingStatus')
@props(['status'])

@switch($status)
    @case(BookingStatus::Tentative)
        @php($color = 'yellow')
    @break

    @case(BookingStatus::Confirmed)
        @php($color = 'lime')
    @break

    @default
        @php($color = 'pink')
@endswitch

<x-badge :color="$color" {{ $attributes }}>{{ __("app.booking.status.{$status->value}") }}</x-badge>
