<div x-sync id="alerts" aria-live="polite" class="w-full sm:max-w-md">
    @if (Session::has('alert'))
        <x-alert :alert="Session::pull('alert')" />
    @endif
</div>
