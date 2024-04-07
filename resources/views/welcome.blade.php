<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased leading-normal tracking-normal bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div class="flex flex-col sm:justify-center items-center sm:mx-6">
        <h1 class="text-center font-semibold text-2xl text-blue-400 uppercase py-6">
            {{ config('app.name', 'Climbing Team') }}
        </h1>

        <div class="w-full sm:max-w-xl bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <img src="{{ asset('images/about/climber.jpg') }}"
                srcset="{{ asset('images/about/climber.jpg') }}, {{ asset('images/about/climber@2x.jpg') }} 2x"
                alt="Child climbing" width="576" height="296" class="w-full max-w-full" />

            <div class="px-6 py-4 space-y-4">
                <p>Climbing and Abseiling sessions at Fox Coverts Campsite are run by our team of qualified climbing
                    instructors. Groups with their own instructors and equipment wishing to hire the climbing tower
                    should contact the Climbing Manager for advice.</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dfn class="not-italic font-medium block">Climbing suitable for</dfn>
                        All age groups
                    </div>
                    <div>
                        <dfn class="not-italic font-medium block">Abseiling suitable for</dfn>
                        Ages 8 and above
                    </div>
                    <div>
                        <dfn class="not-italic font-medium block">Minimum booking</dfn>
                        2 hours
                    </div>
                    <div>
                        <dfn class="not-italic font-medium block">Availability</dfn>
                        April to October
                    </div>
                    <div class="col-span-2">
                        <dfn class="not-italic font-medium block">Number of participants per hour</dfn>
                        12 aged 8 or older.
                        <em class="not-italic text-gray-500">Please ask for advice about younger groups.</em>
                    </div>
                </div>

                <div class="flex justify-center">
                    <x-button.primary href="https://foxcoverts.org.uk/activities/climbing/">
                        <span>Prices and Booking</span>
                        <x-icon.external-link class="ml-1 h-4 w-4 stroke-current"
                            aria-label="({{ __('external link') }})" />
                    </x-button.primary>
                </div>

                <p class="text-center group"><a href="https://foxcoverts.org.uk/activities/climbing/">Please visit
                        <em
                            class="not-italic text-blue-400 dark:text-blue-600 underline group-hover:text-sky-400 dark:group-hover:text-sky-600">foxcoverts.org.uk</em>
                        for prices and to book.</a></p>
            </div>
        </div>

        <div class="w-full max-w-xl">
            <a class="block w-full text-center py-6 text-gray-500 hover:text-zinc-800 dark:hover:text-zinc-200 hover:underline"
                href="{{ route('dashboard') }}">@lang('Go to Team Dashboard')</a>
        </div>
    </div>
</body>

</html>
