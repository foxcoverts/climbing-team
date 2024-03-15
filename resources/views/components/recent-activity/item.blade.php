@if (!$slot->isEmpty())
    <div {{ $attributes }}>
        {{ $time }}
        {{ $slot }}
    </div>
@endif
