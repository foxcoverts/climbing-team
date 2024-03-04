<x-mail::message>

# {{ __('Booking Cancelled') }}

**{{ __('When') }}**<br>
{{ $date }}

**{{ __('Location') }}**<br>
{{ $booking->location }}

**{{ __('Activity') }}**<br>
{{ $booking->activity }}

**{{ __('Group') }}**<br>
{{ $booking->group_name }}

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>
