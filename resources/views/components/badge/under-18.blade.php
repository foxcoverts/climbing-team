@props(['under18' => true])

@if ($under18)
    <x-badge {{ $attributes->merge(['color' => 'pink', 'label' => __('Under 18')]) }} />
@endif
