@use('App\Enums\BookingAttendeeStatus')
@props(['attendance' => null])
@if ($attendance instanceof App\Models\BookingAttendance)
    @php($attendance = $attendance->status)
@elseif (is_string($attendance))
    @php($attendance = BookingAttendeeStatus::tryFrom($attendance))
@endif
@switch($attendance)
    @case(BookingAttendeeStatus::Declined)
        <x-icon.outline.close {{ $attributes }} />
    @break

    @case(BookingAttendeeStatus::NeedsAction)
    @case(null)
        <x-icon.outline.question {{ $attributes }} />
    @break

    @default
        <x-icon.outline.checkmark {{ $attributes }} />
@endswitch
