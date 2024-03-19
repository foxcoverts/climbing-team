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

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="font-sans antialiased leading-normal tracking-normal bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100">
    <div class="flex flex-col sm:justify-center items-center mt-6 sm:m-6 gap-6">
        <h1 class="items-center font-semibold text-2xl text-blue-400 uppercase">
            {{ config('app.name', 'Climbing Team') }}
        </h1>

        <div class="w-full sm:max-w-xl bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <img src="{{ asset('about/climber.jpg') }}"
                srcset="{{ asset('about/climber.jpg') }}, {{ asset('about/climber@2x.jpg') }} 2x" alt="Child climbing"
                width="576" height="296" class="w-full max-w-full" />

            <div class="px-6 py-4 space-y-4">
                <p>Climbing and Abseiling sessions at Fox Coverts Campsite are run by our team of qualified climbing
                    instructors. Groups with their own instructors and equipment wishing to hire the climbing tower
                    should
                    contact the Climbing Manager for advice.</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <dfn class="not-italic font-medium block">Climbing Suitable for</dfn>
                        All age groups
                    </div>
                    <div>
                        <dfn class="not-italic font-medium block">Abseiling Suitable for</dfn>
                        Ages 8 and above
                    </div>
                    <div>
                        <dfn class="not-italic font-medium block">Minimum Booking</dfn>
                        2 hours
                    </div>
                    <div>
                        <dfn class="not-italic font-medium block">Availability</dfn>
                        April to October
                    </div>
                    <div class="col-span-2">
                        <dfn class="not-italic font-medium block">Number of hourly participants</dfn>
                        12 Cubs or older<br>
                        Contact the Climbing Manager for advice about younger groups.
                    </div>
                </div>

                <p>Please visit <a href="https://foxcoverts.org.uk/activities/climbing/"
                        class="text-blue-400 dark:text-blue-600 underline hover:text-sky-400 dark:hover:text-sky-600">foxcoverts.org.uk</a>
                    for prices and to book.</p>
            </div>
        </div>

        @auth
            <div class="w-full max-w-xl text-center">
                <a class="text-gray-500 hover:text-zinc-800 dark:hover:text-zinc-200 hover:underline"
                    href="{{ route('dashboard') }}">@lang('Go to Team Dashboard')</a>
            </div>
        @endauth
    </div>
</body>

</html>
