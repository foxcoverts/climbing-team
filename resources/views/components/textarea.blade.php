@props(['disabled' => false, 'value'])

<textarea x-data="{
    resize() {
        $el.style.height = '0px';
        $el.style.height = $el.scrollHeight + 'px'
    },
}" x-init="$nextTick(resize)" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' =>
        'border-gray-300 disabled:cursor-not-allowed disabled:text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:disabled:text-gray-600 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm resize-y',
    'style' => 'color-scheme: light dark',
]) !!}
    @input="resize()">{{ $value ?? $slot }}</textarea>
