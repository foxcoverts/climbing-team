@if (session('status'))
    <x-alert :restore="session('restore')">{{ session('status') }}</x-alert>
@endif
@if (session('error'))
    <x-alert color='red'>{{ session('error') }}</x-alert>
@endif
@if ($errors->any())
    <x-alert color='red'>{{ $errors->first() }}</x-alert>
@endif
