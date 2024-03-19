@props(['assets' => [], 'title' => ''])
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

    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}" color="#7815d2">
    <meta name="msapplication-TileColor" content="#603cba">
    <meta name="theme-color" content="#ffffff">

    <!-- Scripts -->
    @vite(Arr::flatten(['resources/css/app.css', 'resources/js/app.js', $assets]))
</head>

<body
    class="font-sans antialiased leading-normal tracking-normal bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100"
    x-data="{ sidebarOpen: false }">
    <div class="w-full  min-h-screen flex flex-col" id="main-content">
        <x-layout.app.topbar />
        <x-layout.app.sidebar />

        <main class="flex-grow lg:pl-64 w-full text-gray-900 dark:text-white bg-white dark:bg-gray-800 flex flex-col">
            <x-layout.app.alerts />

            {{ $slot }}
        </main>

        <x-layout.app.footer />
    </div>
</body>

</html>
