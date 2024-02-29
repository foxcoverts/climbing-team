@if (Session::has('alert.info'))
    <x-alert :restore="Session::pull('restore')">{{ Session::pull('alert.info') }}</x-alert>
@endif
@if (Session::has('alert.error'))
    <x-alert color="red">{{ Session::pull('alert.error') }}</x-alert>
@endif
