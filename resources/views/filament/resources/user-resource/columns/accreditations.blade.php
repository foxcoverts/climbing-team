<div class="flex gap-1.5 flex-wrap">
    @foreach ($getRecord()->accreditations as $accreditation)
        <x-filament::badge :color="$accreditation->getColor()">
            {{ $accreditation->getLabel() }}
        </x-filament::badge>
    @endforeach
</div>