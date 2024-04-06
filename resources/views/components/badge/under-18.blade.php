@props(['under18' => true, 'color' => 'pink', 'icon' => null, 'label' => __('Under 18')])
@if ($under18)
    <x-badge :$color :$icon :$label {{ $attributes }} />
@endif
