@props(['value' => $slot])
<label
    {{ $attributes->merge(['class' => 'block not-italic font-medium text-gray-900 dark:text-gray-100']) }}>{{ $value }}</label>
