@props(['options', 'except' => [], 'label_key' => 'id', 'value_key' => 'id'])
@aware(['value'])

@if (!is_array($except))
    @php($except = [$except])
@endif

@foreach ($options as $option)
    @unless (in_array($option->$value_key, $except))
        <option value="{{ $option->$value_key }}" @selected($value == $option->$value_key)>
            {{ $option->$label_key }}
        </option>
    @endunless
@endforeach
