<x-mail::message>

# {{ $title }}

<x-mail::panel>
@empty($reason)
**@lang('This booking has been cancelled')**
@else
**@lang('Reason'):**<br>
{{ $reason }}
@endempty
</x-mail::panel>

**@lang('When')**<br>
{{ $when }}

**@lang('Location')**<br>
{{ $booking->location }}

**@lang('Activity')**<br>
{{ $booking->activity }}

**@lang('Group')**<br>
{{ $booking->group_name }}

@lang('Thanks,')<br>
{{ config('app.name') }}
</x-mail::message>
