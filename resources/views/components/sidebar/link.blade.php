@props(['route', 'matchRoutes' => null, 'fragment' => '', 'icon', 'label'])
<a href="{{ route($route) . $fragment }}" @class([
    'w-full flex items-center text-blue-400 h-10 pl-4 sm:pl-8 lg:pl-4 hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-200 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:active:bg-gray-700',
    'bg-white dark:bg-gray-800 dark:text-white' => Route::is(
        $matchRoutes ?? $route),
])>
    <svg class="h-5 w-5 fill-current mr-2" viewBox="0 0 20 20">
        {{ $icon }}
    </svg>
    <span class="text-gray-700 dark:text-gray-200">{{ $label }}</span>
</a>
