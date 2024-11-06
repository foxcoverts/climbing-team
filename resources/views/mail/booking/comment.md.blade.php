<x-mail::message>

# {{ $title }}

**{{ $author }}**{{ $author_status }} wrote:

<x-mail::panel>
{!! $body !!}
</x-mail::panel>

<x-mail::button :url="$comment_url">
@lang('View Comment')
</x-mail::button>

# @lang('Booking Details')

**@lang('When')**<br>
{{ $when }}

**@lang('Location')**<br>
{{ $booking->location }}

**@lang('Activity')**<br>
{{ $booking->activity }}

<x-slot:subcopy>
@lang(
    "If you are having trouble clicking the \":actionText\" button, copy and paste the URL below\n".
    'into your web browser:',
    [
        'actionText' => __('View Comment'),
    ]
) <x-mail::url-link>{{ $booking_url }}</x-mail::url-link>
</x-slot:subcopy>

@lang('Thanks,')<br>
{{ config('app.name') }}
</x-mail::message>
