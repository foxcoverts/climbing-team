@props([
    'active',
    'color' => $active ? 'lime' : 'gray',
    'icon' => null,
    'label' => $active ? __('Active') : __('Inactive'),
])
<x-badge :$color :$icon :$label {{ $attributes }} />
