@props(['url', 'label' => null, 'color' => 'primary'])
<td style="padding: 0 4px;">
<a href="{{ $url }}" class="button button-{{ $color }}" target="_blank" rel="noopener">{{ $label ?? $slot ?? '' }}</a>
</td>
