<div class="w-full sm:max-w-md">
    @if (Session::has('alert.info'))
        <x-alert :restore="session('restore')" class="mt-6">{{ Session::pull('alert.info') }}</x-alert>
    @endif
    @if (Session::has('alert.error'))
        <x-alert color="red" class="mt-6">{{ Session::pull('alert.error') }}</x-alert>
    @endif
</div>
