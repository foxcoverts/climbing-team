<x-button
    {{ $attributes->class([
            'bg-red-600 border-transparent text-white hover:bg-red-500 active:bg-red-700 focus:ring-red-500 dark:focus:ring-offset-gray-800',
        ])->merge(['type' => 'submit']) }}>
    {{ $slot }}
</x-button>
