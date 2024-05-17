@props(['color' => '', 'label' => $slot])
@switch($color)
    @case('primary')
        @php($color_classes = 'bg-gray-800 dark:bg-gray-200 border-transparent text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:ring-indigo-500 dark:focus:ring-offset-gray-800')
    @break

    @case('secondary')
        @php($color_classes = 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800')
    @break

    @case('danger')
        @php($color_classes = 'bg-red-600 border-transparent text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500 dark:focus:ring-offset-gray-800')
    @break

    @case('disabled')
        @php($color_classes = 'cursor-not-allowed text-white bg-gray-300 dark:bg-gray-500')
    @break

    @default
        @php($color_classes = '')
@endswitch

@if ($attributes->has('href'))
    <a
        {{ $attributes->except('type')->class([
                $color_classes,
                'inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150',
            ]) }}>
        {{ $label }}
    </a>
@else
    <button
        {{ $attributes->class([
                $color_classes,
                'inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 disabled:cursor-not-allowed disabled:text-white disabled:bg-gray-300 disabled:hover:bg-gray-300 disabled:dark:bg-gray-500 disabled:dark:hover:bg-gray-500',
            ])->merge([
                'type' => 'button',
            ]) }}>
        {{ $label }}
    </button>
@endif
