@props(['changeable', 'attendee'])
@can('view', $changeable->attendees->find($attendee)?->attendance)
    <a href="{{ route('booking.attendee.show', [$changeable, $attendee]) }}" class="font-medium">{{ $attendee->name }}</a>
@else
    <strong class="font-medium">{{ $attendee->name }}</strong>
@endcan
