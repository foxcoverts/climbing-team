@use('App\Enums\BookingAttendeeStatus')
<div class="border-l-2 ml-2 pl-2" id="{{ $attendee->id }}">
    <p>
        @include($attendee_link, [
            'changeable' => $change->changeable,
            'attendee' => $attendee->attendee,
        ])
        @switch ($attendee->attendee_status)
            @case(BookingAttendeeStatus::Accepted)
                {{ __('will be going to') }}
            @break

            @case(BookingAttendeeStatus::Tentative)
                {{ __('may be able to attend') }}
            @break

            @case(BookingAttendeeStatus::Declined)
                {{ __('cannot attend') }}
            @break
        @endswitch
        @include($changeable_link, [
            'changeable' => $change->changeable,
            'after' => '.',
        ])
    </p>
    @if ($attendee->attendee_comment)
        <div>
            @include($attendee_link, [
                'changeable' => $change->changeable,
                'attendee' => $change->author,
            ])
            {{ __('commented') }}
            @include($changeable_link, [
                'changeable' => $change->changeable,
                'before' => 'on ',
                'after' => ':',
                'show' => 'link',
            ])
        </div>
        <p class="border-l-2 ml-2 pl-2">{{ $attendee->attendee_comment }}</p>
    @endif
</div>
