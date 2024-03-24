@use('App\Enums\AttendeeStatus')
@props(['attendance' => null])
@if ($attendance instanceof App\Models\Attendance)
    @php($attendance = $attendance->status)
@elseif (is_string($attendance))
    @php($attendance = AttendeeStatus::tryFrom($attendance))
@endif
@switch($attendance)
    @case(AttendeeStatus::Declined)
        <x-icon.close-outline {{ $attributes }} />
    @break

    @case(AttendeeStatus::NeedsAction)
    @case(null)
        <x-icon.question-outline {{ $attributes }} />
    @break

    @default
        <x-icon.checkmark-outline {{ $attributes }} />
@endswitch
