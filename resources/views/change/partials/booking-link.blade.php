@props(['where' => 'end', 'before' => '', 'after' => '', 'show' => 'always'])
@if ($show == 'always' || $show == 'link')
    {{ $before }}
    <a href="{{ route('booking.show', $booking) }}" class="font-medium">
        {{ __(':activity on :date', [
            'activity' => $booking->activity,
            'date' => localDate($booking->start_at, $booking->timezone)->toFormattedDayDateString(),
        ]) }}</a>{{ $after }}
@endif
