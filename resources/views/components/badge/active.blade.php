@props(['active' => true])

@if ($active)
    <x-badge color="lime" {{ $attributes }}>{{ __('Active') }}</x-badge>
@else
    <x-badge color="gray" {{ $attributes }}>{{ __('Inactive') }}</x-badge>
@endif
