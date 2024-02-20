<x-button
    {{ $attributes->class([
            'bg-gray-800 dark:bg-gray-200 border-transparent text-white disabled:cursor-not-allowed disabled:bg-gray-300 disabled:hover:bg-gray-300 disabled:dark:bg-gray-500 disabled:dark:hover:bg-gray-500 dark:text-gray-800 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:ring-indigo-500 dark:focus:ring-offset-gray-800',
        ])->merge(['type' => 'submit']) }}>
    {{ $slot }}
</x-button>
