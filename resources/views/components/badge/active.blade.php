@props(['active' => true])

@if ($active)
    <x-badge color="lime" {{ $attributes }}>@lang('Active')</x-badge>
@else
    <x-badge color="gray" {{ $attributes }}>@lang('Inactive')</x-badge>
@endif
