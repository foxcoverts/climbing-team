<x-button
    {{ $attributes->class([
            'bg-gray-800 dark:bg-gray-200 border-transparent text-white dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:ring-indigo-500 dark:focus:ring-offset-gray-800',
        ])->merge(['type' => 'submit']) }}>
    {{ $slot }}
</x-button>
