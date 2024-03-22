@props(['date' => Carbon\Carbon::now()])
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
    {{ $attributes->merge([
        'aria-label' => 'Calendar',
        'role' => 'image',
    ]) }}>
    <path d="M512 455c0 32-25 57-57 57H57c-32 0-57-25-57-57V128c0-31 25-57 57-57h398c32 0 57 26 57 57z" fill="#e0e7ec" />
    <path
        d="M484 0h-47c2 4 4 9 4 14a28 28 0 1 1-53-14H124c3 4 4 9 4 14A28 28 0 1 1 75 0H28C13 0 0 13 0 28v157h512V28c0-15-13-28-28-28z"
        fill="#dd2f45" />

    <text id="month" x="256" y="150" fill="#fff" font-family="monospace" font-size="130px"
        style="text-anchor: middle">{{ $date->format('M') }}</text>

    <text id="day" x="256" y="400" fill="#66757f" font-family="monospace" font-size="256px"
        style="text-anchor: middle">{{ $date->format('j') }}</text>

    <text id="weekday" x="256" y="480" fill="#66757f" font-family="monospace" font-size="64px"
        style="text-anchor: middle">{{ $date->format('l') }}</text>
</svg>
