@use('App\Enums\Accreditation')
@props(['accreditation'])

@switch($accreditation)
    @case(Accreditation::PermitHolder)
        @php($color = 'sky')
    @break

    @case(Accreditation::ManageBookings)
    @case(Accreditation::ManageUsers)
        @php($color = 'yellow')
    @break

    @default
        @php($color = 'gray')
@endswitch

<x-badge :color="$color" {{ $attributes }}>{{ __("app.user.accreditation.{$accreditation->value}") }}</x-badge>
