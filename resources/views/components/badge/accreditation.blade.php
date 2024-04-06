@use('App\Enums\Accreditation')
@props([
    'accreditation',
    'color' => 'yellow',
    'icon' => null,
    'label' => __('app.user.accreditation.' . $accreditation->value),
])
<x-badge :$color :$icon :$label {{ $attributes }} />
