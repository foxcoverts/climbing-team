<x-mail::message>

# {{ $title }}

@if (!empty($change_summary))
<x-mail::panel>
**{{ __('This booking has been updated') }}**<br>
**{{ __('Changes') }}:** {{ $change_summary }}.
</x-mail::panel>
@endif

@if ($status_changed != '')
**{{ __('Status') }}**{{ $status_changed }}<br>
{{ __("app.booking.status.{$booking->status->value}") }}
@endif

**{{ __('When') }}**{{ $when_changed }}<br>
{{ $when }}

**{{ __('Location') }}**{{ $location_changed }}<br>
{{ $booking->location }}

**{{ __('Activity') }}**{{ $activity_changed }}<br>
{{ $booking->activity }}

@if ($booking->lead_instructor)
**{{ __('Lead Instructor') }}**{{ $lead_instructor_changed }}<br>
{{ $booking->lead_instructor->name }}
@endif

**{{ __('Group') }}**{{ $group_changed }}<br>
{{ $booking->group_name }}

@if ($booking->notes)
<x-markdown>
**{{ __('Notes') }}**{{ $notes_changed }}<br>
{{ $booking->notes }}
</x-markdown>
@endif

<x-mail::button :url="$button_url">
{{ $button_label }}
</x-mail::button>

{{ __('Thanks,') }}<br>
{{ config('app.name') }}
</x-mail::message>
