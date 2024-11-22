@php
    ['active', 'role', 'section:under-18', 'section:parent', 'permit-holder', 'key-holder', 'accreditations']
@endphp

<div class="flex gap-1.5 flex-wrap">
    @unless ($getRecord()->isActive())
        <x-filament::badge color="gray">
            {{ __('Inactive') }}
        </x-filament::badge>
    @endunless

    <x-filament::badge :color="$getRecord()->role->getColor()">
        {{ $getRecord()->role->getLabel() }}
    </x-filament::badge>

    @if ($getRecord()->isUnder18() || $getRecord()->isParent())
        <x-filament::badge :color="$getRecord()->section->getColor()">
            {{ $getRecord()->section->getLabel() }}
        </x-filament::badge>
    @endif

    @if ($getRecord()->isPermitHolder())
        <x-filament::badge :color="Filament\Support\Colors\Color::Sky">
            {{ __('Permit Holder') }}
        </x-filament::badge>
    @endif

    @if ($getRecord()->isKeyHolder())
        <x-filament::badge :color="Filament\Support\Colors\Color::Yellow">
            @svg('heroicon-o-key', 'fi-badge-icon h-4 w-4')
        </x-filament::badge>
    @endif

    @foreach ($getRecord()->accreditations as $accreditation)
        <x-filament::badge :color="$accreditation->getColor()">
            {{ $accreditation->getLabel() }}
        </x-filament::badge>
    @endforeach
</div>