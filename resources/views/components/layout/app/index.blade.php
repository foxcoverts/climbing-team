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

<body class="font-sans antialiased bg-gray-100" x-data="{ sidebarOpen: false, alertOpen: true }">
    <div class="leading-normal tracking-normal" id="main-body">
        <div class="flex flex-wrap">
            <div class="w-full bg-gray-100 min-h-screen flex flex-col" id="main-content">
                <x-layout.app.topbar />

                <div class="flex-1 lg:flex lg:flex-row">
                    <x-layout.app.sidebar />

                    <main class="lg:pl-64 w-full">
                        <x-layout.app.alert />

                        {{ $slot }}
                    </main>
                </div>

                <x-layout.app.footer />
            </div>
        </div>
    </div>
</body>

</html>
