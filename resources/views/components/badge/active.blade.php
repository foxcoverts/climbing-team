@props(['active' => true])

@if ($active)
    <x-badge {{ $attributes->merge(['color' => 'lime', 'label' => __('Active')]) }} />
@else
    <x-badge {{ $attributes->merge(['color' => 'gray', 'label' => __('Inactive')]) }} />
@endif
