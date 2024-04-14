@props(['url', 'color' => 'primary'])
<td>
<a href="{{ $url }}" class="button button-{{ $color }}" target="_blank" rel="noopener">{{ $slot }}</a>
</td>
