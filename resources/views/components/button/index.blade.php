@if ($attributes->has('href'))
    <a
        {{ $attributes->except('type')->merge([
            'class' =>
                'inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150',
        ]) }}>
        {{ $slot }}
    </a>
@else
    <button
        {{ $attributes->merge([
            'class' =>
                'inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150',
            'type' => 'button',
        ]) }}>
        {{ $slot }}
    </button>
@endif
