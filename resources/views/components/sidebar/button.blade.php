<form method="{{ $method }}" action="{{ route($route) }}">
    @csrf
    <button @class([
        'w-full flex items-center text-blue-400 h-10 pl-4 hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-200 rounded-lg',
        'bg-gray-50' => Route::is($matchRoutes ?? $route),
    ])>
        <svg class="h-6 w-6 fill-current mr-2" viewBox="0 0 20 20">
            {{ $icon }}
        </svg>
        <span class="text-gray-700">{{ $label }}</span>
    </button>
</form>
