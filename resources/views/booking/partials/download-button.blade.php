@use('App\Enums\AttendeeStatus')
@props(['booking', 'attendance'])
@if (
    $booking->isFuture() &&
        !$booking->isCancelled() &&
        in_array($attendance?->status, [AttendeeStatus::Accepted, AttendeeStatus::Tentative]))
    <x-button.secondary :href="route('booking.show.ics', $booking)" :title="__('Add to Calendar')">
        <x-icon.download class="h-4 w-4 fill-current" />
    </x-button.secondary>
@endif
