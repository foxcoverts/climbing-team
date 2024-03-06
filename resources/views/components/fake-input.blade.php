@props(['value' => $slot])
<div aria-readonly="true"
    {{ $attributes->class([
        'form-input inline-flex cursor-default aria-disabled:cursor-not-allowed aria-disabled:text-gray-400 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:aria-disabled:text-gray-600 rounded-md shadow-sm',
    ]) }}>
    {{ $value }}
</div>
