@props(['where' => 'end', 'before' => '', 'after' => '', 'show' => 'always'])
@if ($show == 'always' || $show == 'link')
    {{ $before }}
    <a href="{{ route('booking.show', $changeable) }}" class="font-medium">
        {{ __(':activity on :date', [
            'activity' => $changeable->activity,
            'date' => localDate($changeable->start_at, $changeable->timezone)->toFormattedDayDateString(),
        ]) }}</a>{{ $after }}
@endif
