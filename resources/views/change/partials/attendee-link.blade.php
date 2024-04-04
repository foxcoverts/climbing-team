@props(['booking', 'attendee'])
@can('view', $booking->attendees->find($attendee)?->attendance)
    <a href="{{ route('booking.attendee.show', [$booking, $attendee]) }}" class="font-medium">{{ $attendee->name }}</a>
@else
    <strong class="font-medium">{{ $attendee->name }}</strong>
@endcan
