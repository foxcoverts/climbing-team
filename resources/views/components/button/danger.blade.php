@props(['label' => $slot])
<x-button :$label {{ $attributes->merge(['type' => 'submit', 'color' => 'danger']) }} />
