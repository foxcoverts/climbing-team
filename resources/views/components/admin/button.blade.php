@switch($style ?? 'default')
    @case('primary')
        @php
            $colours = 'text-white bg-sky-500 hover:bg-sky-600 focus:bg-sky-600 active:bg-sky-700 dark:bg-blue-800 dark:hover:bg-blue-700 dark:focus:bg-blue-700 dark:active:bg-blue-800';
            @endphp
        @break

    @case('danger')
        @php
            $colours = 'text-white bg-red-500 hover:bg-red-600 focus:bg-red-600 active:bg-red-700 dark:bg-red-900 dark:hover:bg-red-800 dark:focus:bg-red-800 dark:active:bg-red-700';
            @endphp
        @break

    @default
        @php
            $colours = 'text-gray-700 dark:text-gray-300 bg-gray-100 hover:bg-gray-200 focus:bg-gray-200 active:bg-gray-300 dark:bg-gray-800 dark:hover:bg-gray-900 dark:focus:bg-gray-900 dark:active:bg-gray-950';
            @endphp
@endswitch

@if(empty($href))
<button {{ $attributes->merge(['class' => $colours])->merge(['class' => 'block px-2 py-2 text-left text-m leading-5 focus:outline-none transition duration-150 ease-in-out rounded']) }}>{{ $slot }}</button>
@else
<a {{ $attributes->merge(['href' => $href, 'class' => $colours])->merge(['class' => 'block px-2 py-2 text-left text-m leading-5 focus:outline-none transition duration-150 ease-in-out rounded']) }}>{{ $slot }}</a>
@endif
