@props(['timezone'])
@aware(['value'])

<option value="{{ $timezone }}" @selected($value == $timezone)>
    {{ $timezone }} ({{ $timezone->toOffsetName() }})
</option>
