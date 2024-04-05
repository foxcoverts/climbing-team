@props(['label' => $slot])
<x-button :$label {{ $attributes->merge(['color' => 'secondary']) }} />
