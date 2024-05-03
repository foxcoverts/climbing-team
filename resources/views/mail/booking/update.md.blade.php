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
{{-- new line --}}

**@lang('When')**{{ $when_changed }}<br>
{{ $when }}

**@lang('Location')**{{ $location_changed }}<br>
{{ $booking->location }}

**@lang('Activity')**{{ $activity_changed }}<br>
{{ $booking->activity }}

@isset($booking->lead_instructor)
**@lang('Lead Instructor')**{{ $lead_instructor_changed }}<br>
{{ $booking->lead_instructor->name }}
@endisset
{{-- new line --}}

**@lang('Group')**{{ $group_changed }}<br>
{{ $booking->group_name }}

@if ($booking->notes)
**@lang('Notes')**{{ $notes_changed }}<br>
{!! $booking->notes !!}
@endif

<x-mail::button :url="$booking_url">
@lang('View Booking')
</x-mail::button>

<x-slot:subcopy>
@lang(
    "If you're having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => __('View Booking'),
    ]
) <span class="break-all">{{ $booking_url }}</span>
</x-slot:subcopy>

@lang('Thanks,')<br>
{{ config('app.name') }}
</x-mail::message>
