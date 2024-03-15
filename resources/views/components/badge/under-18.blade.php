@props(['under18' => true])

@if ($under18)
    <x-badge color="pink" {{ $attributes }}>@lang('Under 18')</x-badge>
@endif
