@use('App\Enums\AttendeeStatus')
<div class="border-l-2 ml-2 pl-2" id="{{ $attendee->id }}">
    <p>
        @include('change.partials.attendee-link', [
            'booking' => $change->booking,
            'attendee' => $attendee->attendee,
        ])
        @switch ($attendee->attendee_status)
            @case(AttendeeStatus::Accepted)
                {{ __('will be going to') }}
            @break

            @case(AttendeeStatus::Tentative)
                {{ __('may be able to attend') }}
            @break

            @case(AttendeeStatus::Declined)
                {{ __('cannot attend') }}
            @break
        @endswitch
        @include($booking_link, [
            'booking' => $change->booking,
            'after' => '.',
        ])
    </p>
    @if ($attendee->attendee_comment)
        <div>
            @include('change.partials.attendee-link', [
                'booking' => $change->booking,
                'attendee' => $change->author,
            ])
            {{ __('commented') }}
            @include($booking_link, [
                'booking' => $change->booking,
                'before' => 'on ',
                'after' => ':',
                'show' => 'link',
            ])
        </div>
        <p class="border-l-2 ml-2 pl-2">{{ $attendee->attendee_comment }}</p>
    @endif
</div>
