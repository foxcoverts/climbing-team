@use('App\Enums\AttendeeStatus')
@props(['attendance' => null])
@if ($attendance instanceof App\Models\Attendance)
    @php($attendance = $attendance->status)
@elseif (is_string($attendance))
    @php($attendance = AttendeeStatus::tryFrom($attendance))
@endif
@switch($attendance)
    @case(AttendeeStatus::Declined)
        <x-icon.outline.close {{ $attributes }} />
    @break

    @case(AttendeeStatus::NeedsAction)
    @case(null)
        <x-icon.outline.question {{ $attributes }} />
    @break

    @default
        <x-icon.outline.checkmark {{ $attributes }} />
@endswitch
