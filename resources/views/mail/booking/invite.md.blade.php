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

<x-mail::action-panel>
<x-mail::center>
## @lang('Can you attend this event?')
</x-mail::center>

<x-mail::button-group>
<x-mail::button-group.button :url="$accept_url" color="success" :label="__('Yes')" />
<x-mail::button-group.button :url="$decline_url" color="error" :label="__('No')" />
<x-mail::button-group.button :url="$tentative_url" :label="__('Maybe')" />
</x-mail::button-group>
</x-mail::action-panel>

@isset($respond_url)
<x-slot:subcopy>
@lang(
    "If you're having trouble clicking the buttons above, copy and paste the URL below\n".
    'into your web browser:'
) <x-mail::url-link>{{ $respond_url }}</x-mail::url-link>
</x-slot:subcopy>
@endisset

@lang('Thanks,')<br>
{{ config('app.name') }}
</x-mail::message>
