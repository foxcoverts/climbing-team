@use('App\Enums\BookingAttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
@props(['attendee', 'booking'])
@php($attendance = $attendee->attendance)

@if (!is_null($attendance))
    <div
        class="flex gap-2 px-4 py-2 border rounded-md font-semibold text-xs uppercase tracking-widest bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm disabled:opacity-25">
        <x-icon.attendance :$attendance class="w-4 h-4 fill-current" />
        @if ($attendee->id === $booking->lead_instructor_id)
            {{ __('Lead Instructor') }}
        @else
            {{ __("app.booking.attendee.status.{$attendance->status->value}") }}
        @endif
    </div>
@endif
