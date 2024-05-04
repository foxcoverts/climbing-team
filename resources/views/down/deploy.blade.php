<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Climbing Team') }}</title>

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

    @vite(['resources/css/app.css'])
</head>

<body
    class="font-sans antialiased leading-normal tracking-normal bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div class="flex flex-col sm:justify-center items-center sm:mx-6">
        <h1 class="text-center font-semibold text-2xl text-blue-400 uppercase py-6">
            {{ config('app.name', 'Climbing Team') }}
        </h1>

        <div class="w-full sm:max-w-xl bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <hr style="background-image: repeating-linear-gradient(-55deg, rgba(255, 222, 26, .8), rgba(255, 222, 26, .8) 6px, rgba(0, 0, 0, .8) 6px, rgba(0, 0, 0, .8) 15px); background-position: left;"
                class="h-12 w-full" />
            <div class="px-6 py-4 space-y-4">
                <h2 class="text-center font-medium text-lg">{{ __('Maintenance') }}</h2>

                <p>{{ __('We are performing some essential maintenance right now and should be back up in a few minutes. Please try again soon.') }}
                </p>
            </div>
            <hr style="background-image: repeating-linear-gradient(-55deg, rgba(255, 222, 26, .8), rgba(255, 222, 26, .8) 6px, rgba(0, 0, 0, .8) 6px, rgba(0, 0, 0, .8) 15px); background-position: left;"
                class="h-12 w-full" />
        </div>
    </div>
</body>

</html>
