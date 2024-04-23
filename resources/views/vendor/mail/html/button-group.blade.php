@props([
    'align' => 'center',
])
<table class="button-group" align="{{ $align }}" width="100%" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table width="100%" border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td align="{{ $align }}">
<table border="0" cellpadding="0" cellspacing="0" role="presentation">
<tr>
{{ $slot }}
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
