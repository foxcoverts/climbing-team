@props(['label' => null, 'url'])
{{ $label ?? ($slot ?? '') }}: {{ $url }}
