<x-mail::message>

# {{ $title }}

<x-mail::panel>
{{ __('This booking has been cancelled.') }}
</x-mail::panel>

**{{ __('When') }}**<br>
{{ $when }}

**{{ __('Location') }}**<br>
{{ $booking->location }}

**{{ __('Activity') }}**<br>
{{ $booking->activity }}

**{{ __('Group') }}**<br>
{{ $booking->group_name }}

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>
