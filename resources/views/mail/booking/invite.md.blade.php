<x-mail::message>

# {{ $title }}

@if (!empty($changed_summary))
<x-mail::panel>
**@lang('This booking has been updated')**<br>
**@lang('Changes'):** {{ $changed_summary }}.
</x-mail::panel>
@endif

@if ($status_changed != '')
**@lang('Status')**{{ $status_changed }}<br>
@lang("app.booking.status.{$booking->status->value}")
@endif

**@lang('When')**{{ $when_changed }}<br>
{{ $when }}

**@lang('Location')**{{ $location_changed }}<br>
{{ $booking->location }}

**@lang('Activity')**{{ $activity_changed }}<br>
{{ $booking->activity }}

@if ($booking->lead_instructor)
**@lang('Lead Instructor')**{{ $lead_instructor_changed }}<br>
{{ $booking->lead_instructor->name }}
@endif

**@lang('Group')**{{ $group_changed }}<br>
{{ $booking->group_name }}

@if ($booking->notes)
<x-markdown>
**@lang('Notes')**{{ $notes_changed }}<br>
{{ $booking->notes }}
</x-markdown>
@endif

<x-mail::button :url="$button_url">
{{ $button_label }}
</x-mail::button>

@lang('Thanks,')<br>
{{ config('app.name') }}
</x-mail::message>
