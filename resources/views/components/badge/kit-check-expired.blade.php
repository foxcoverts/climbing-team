@props(['expired' => true])
@props([
    'expired',
    'color' => match ($expired) {
        false => 'lime',
        default => 'pink',
    },
    'icon' => match ($expired) {
        false => 'outline.checkmark',
        default => 'outline.exclamation',
    },
    'label' => match ($expired) {
        false => __('Good'),
        default => __('Expired'),
    },
])
<x-badge :$color :$icon :$label {{ $attributes }} />
