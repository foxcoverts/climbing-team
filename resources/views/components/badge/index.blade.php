@props(['color' => 'gray', 'label' => $slot])
<span
    {{ $attributes->class([
        'inline-flex items-center rounded-md px-2 py-1 font-medium ring-1 ring-inset',
        'bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 ring-gray-500/25 dark:ring-gray-300/25' =>
            $color == 'gray',
        'bg-lime-50 dark:bg-lime-700 text-lime-600 dark:text-lime-300 ring-lime-500/25 dark:ring-lime-300/25' =>
            $color == 'lime',
        'bg-pink-50 dark:bg-pink-700 text-pink-600 dark:text-pink-300 ring-pink-500/25 dark:ring-pink-300/25' =>
            $color == 'pink',
        'bg-sky-50 dark:bg-sky-700 text-sky-600 dark:text-sky-300 ring-sky-500/25 dark:ring-sky-300/25' => $color == 'sky',
        'bg-yellow-50 dark:bg-yellow-700 text-yellow-600 dark:text-yellow-300 ring-yellow-500/25 dark:ring-yellow-300/25' =>
            $color == 'yellow',
    ]) }}>{{ $label }}</span>
