<x-mail::message>

# {{ __('Invitation') }}

**{{ __('When') }}**<br>
{{ $date }}

**{{ __('Location') }}**<br>
{{ $booking->location }}

**{{ __('Activity') }}**<br>
{{ $booking->activity }}

@if ($booking->lead_instructor)
**{{ __('Lead Instructor') }}**<br>
{{ $booking->lead_instructor->name }}
@endif

**{{ __('Group') }}**<br>
{{ $booking->group_name }}

@if ($booking->notes)
**{{ __('Notes') }}**<br>
{{ $booking->notes }}
@endif

<x-mail::button :url="$url">
{{ __('Respond' )}}
</x-mail::button>

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>
