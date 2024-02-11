@props(['value'])

<label
    {{ $attributes->merge(['class' => "block not-italic font-bold after:content-[':'] text-gray-900 dark:text-gray-100"]) }}>{{ $value ?? $slot }}</label>
