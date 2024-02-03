@props(['options', 'value'])

@php
    // old('value') is not automatically cast to the Enum
    if (is_string($value)) {
        $value = $options::tryFrom($value);
    }
@endphp

<select {{ $attributes }}>
    @foreach ($options::cases() as $option)
        <option value="{{ $option->value }}" @selected($value == $option)>{{ __($option->name) }}</option>
    @endforeach
</select>
