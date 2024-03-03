<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (empty($title))
        <title>{{ config('app.name', 'Climbing Team') }}</title>
    @else
        <title>{{ $title }} - {{ config('app.name', 'Climbing Team') }}</title>
    @endif

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased leading-normal tracking-normal bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
        <div class="items-center">
            <a href="/"
                class="font-semibold text-2xl text-blue-400 uppercase">{{ config('app.name', 'Climbing Team') }}</a>
        </div>

        <x-layout.guest.alerts />

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            @if (!empty($title))
                <div>
                    <h1 class="border-b mb-4 font-bold text-lg">{{ $title }}</h1>
                </div>
            @endif

            {{ $slot }}
        </div>
    </div>
</body>

</html>
