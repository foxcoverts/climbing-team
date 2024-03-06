@props(['options', 'lang' => ':name', 'except' => []])
@aware(['value'])

@php
    // old('value') is not automatically cast to the Enum
    if (is_string($value)) {
        $value = $options::tryFrom($value);
    }
    if (!is_array($except)) {
        $except = [$except];
    }
@endphp

@foreach ($options::cases() as $option)
    @unless (in_array($option, $except))
        <option value="{{ $option->value }}" @selected($value == $option)>
            @lang(
                Str::swap(
                    [
                        ':name' => $option->name,
                        ':value' => $option->value,
                    ],
                    $lang,
                )
            )
        </option>
    @endunless
@endforeach
