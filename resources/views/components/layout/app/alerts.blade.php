<div x-sync id="alerts" aria-live="polite">
    @if (Session::has('alert'))
        <x-alert :alert="Session::pull('alert')" />
    @endif
</div>
