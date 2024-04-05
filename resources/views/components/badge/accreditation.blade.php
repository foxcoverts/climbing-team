@use('App\Enums\Accreditation')
@props(['accreditation'])

@switch($accreditation)
    @case(Accreditation::ManageBookings)
    @case(Accreditation::ManageQualifications)

    @case(Accreditation::ManageUsers)
        @php($color = 'yellow')
    @break

    @default
        @php($color = 'gray')
@endswitch

<x-badge
    {{ $attributes->merge([
        'color' => $color,
        'label' => __('app.user.accreditation.' . $accreditation->value),
    ]) }} />
