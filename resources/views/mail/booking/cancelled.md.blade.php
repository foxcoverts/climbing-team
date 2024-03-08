<x-mail::message>

# {{ $title }}

<x-mail::panel>
@lang('This booking has been cancelled.')
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
