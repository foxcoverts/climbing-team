@use('App\Enums\Accreditation')
@props(['accreditation'])

@switch($accreditation)
    @case(Accreditation::PermitHolder)
        @php($color = 'sky')
    @break

    @default
        @php($color = 'gray')
@endswitch

<x-badge :color="$color">{{ __("app.user.accreditation.{$accreditation->value}") }}</x-badge>
