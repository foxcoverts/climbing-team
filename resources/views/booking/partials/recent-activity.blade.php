<h3 class="text-xl font-medium">@lang('Recent Activity')</h3>

<div class="space-y-2">
    <div>
        <p><span title="{{ localDate($booking->created_at)->toDayDateTimeString() }}" class="cursor-help">
                {{ localDate($booking->created_at)->ago() }}
            </span></p>
        <div class="border-l-2 ml-2 pl-2">@lang('Booking created.')</div>
    </div>

    @foreach ($booking->changes as $change)
        <div id="{{ $change->id }}">
            <p><span title="{{ localDate($change->created_at)->toDayDateTimeString() }}" class="cursor-help">
                    {{ localDate($change->created_at)->ago() }}
                </span></p>
            @foreach ($change->attendees as $attendee)
                <div class="border-l-2 ml-2 pl-2" id="{{ $attendee->id }}">
                    <div><a href="{{ route('user.show', $attendee->attendee) }}"
                            class="font-medium">{{ $attendee->attendee->name }}</a>
                        @switch ($attendee->attendee_status)
                            @case('accepted')
                                will be going to this booking.
                            @break

                            @case('tentative')
                                may be able to attend this booking.
                            @break

                            @case('declined')
                                cannot attend this booking.
                            @break
                        @endswitch
                    </div>
                </div>
            @endforeach
            @foreach ($change->comments as $comment)
                <div class="border-l-2 ml-2 pl-2" id="{{ $comment->id }}">
                    <div><a href="{{ route('user.show', $change->author) }}"
                            class="font-medium">{{ $change->author->name }}</a> commented:</div>
                    <p class="border-l-2 ml-2 pl-2">{{ $comment->body }}</p>
                </div>
            @endforeach
        </div>
    @endforeach
</div>
