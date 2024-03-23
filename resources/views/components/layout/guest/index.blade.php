@props([
    'title' => '',
    'description' => null,
    'canonical' => null,
    'image' => null,
    'image_width' => 0,
    'image_height' => 0,
    'updated' => null,
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    @if (empty($title))
        <title>{{ config('app.name', 'Climbing Team') }}</title>
    @else
        <title>{{ $title }} - {{ config('app.name', 'Climbing Team') }}</title>
        <meta property="og:title" content="{{ $title }}" />
        <meta property="og:site_name" content="{{ config('app.name', 'Climbing Team') }}" />
        <meta property="twitter:title" content="{{ $title }}" />
    @endif
    @isset($canonical)
        <link rel="canonical" href="{{ $canonical }}" />
        <meta property="og:url" content="{{ $canonical }}" />
    @else
        <meta property="og:url" content="{{ url()->current() }}" />
    @endisset
    @isset($description)
        <meta name="description" content="{{ $description }}" />
        <meta property="og:description" content="{{ $description }}" />
        <meta name="twitter:description" content="{{ $description }}" />
    @endisset
    @isset($image)
        <meta property="og:image" content="{{ $image }}" />
        <meta name="twitter:image" content="{{ $image }}" />
        @if ($image->attributes->has('width'))
            <meta property="og:image:width" content="{{ $image->attributes->get('width') }}" />
        @endif
        @if ($image->attributes->has('height'))
            <meta property="og:image:height" content="{{ $image->attributes->get('height') }}" />
        @endif
    @endisset
    @isset($updated)
        <meta property="og:updated_time" content="{{ $updated->toIso8601String() }}" />
    @endisset
    <meta property="og:type" content="website" />
    <meta property="og:locale" content="{{ config('app.locale', 'en') }}" />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@FoxCoverts" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}" />
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}" />
    <link rel="manifest" href="{{ asset('site.webmanifest') }}" />
    <link rel="mask-icon" href="{{ asset('safari-pinned-tab.svg') }}" color="#7815d2" />
    <meta name="msapplication-TileColor" content="#603cba" />
    <meta name="theme-color" content="#ffffff" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased leading-normal tracking-normal bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:py-6">
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
