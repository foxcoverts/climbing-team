@use('App\Enums\AttendeeStatus')
<h3 class="text-xl font-medium">@lang('Recent Activity')</h3>

<div class="space-y-2">
    @include('booking.partials.comment-form')

    @php($lastChange = null)
    @foreach ($booking->changes as $change)
        @if ($lastChange && $lastChange->attendees->pluck('attendee') == $change->attendees->pluck('attendee'))
            {{-- Reduce noise from the same attendee changing their status in a row --}}
            @continue
        @endif
        <div id="{{ $change->id }}">
            <p><span title="{{ localDate($change->created_at)->toDayDateTimeString() }}" class="cursor-help">
                    {{ localDate($change->created_at)->ago() }}
                </span></p>
            @foreach ($change->attendees as $attendee)
                <div class="border-l-2 ml-2 pl-2" id="{{ $attendee->id }}">
                    <p><a href="{{ route('user.show', $attendee->attendee) }}"
                            class="font-medium">{{ $attendee->attendee->name }}</a>
                        @switch ($attendee->attendee_status)
                            @case(AttendeeStatus::Accepted)
                                @lang('will be going to this booking.')
                            @break

                            @case(AttendeeStatus::Tentative)
                                @lang('may be able to attend this booking.')
                            @break

                            @case(AttendeeStatus::Declined)
                                @lang('cannot attend this booking.')
                            @break
                        @endswitch
                    </p>
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
        @php($lastChange = $change)
    @endforeach
    <div>
        <p><span title="{{ localDate($booking->created_at)->toDayDateTimeString() }}" class="cursor-help">
                {{ localDate($booking->created_at)->ago() }}
            </span></p>
        <div class="border-l-2 ml-2 pl-2">@lang('Booking created.')</div>
    </div>
</div>
