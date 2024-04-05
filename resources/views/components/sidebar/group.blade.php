@props(['heading'])
@if (!$slot->isEmpty())
    <div {{ $attributes }}>
        <x-sidebar.heading>{{ $heading }}</x-sidebar.heading>
        {{ $slot }}
    </div>
@endif
