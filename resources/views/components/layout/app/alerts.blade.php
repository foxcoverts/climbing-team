@if (session('alert.info'))
    <x-alert :restore="session('restore')">{{ session('alert.info') }}</x-alert>
@endif
@if (session('alert.error'))
    <x-alert color='red'>{{ session('alert.error') }}</x-alert>
@endif
