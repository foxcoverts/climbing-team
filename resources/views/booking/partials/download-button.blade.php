@use('App\Enums\BookingAttendeeStatus')
@props(['booking', 'attendance'])
@if (
    $booking->isFuture() &&
        !$booking->isCancelled() &&
        in_array($attendance?->status, [BookingAttendeeStatus::Accepted, BookingAttendeeStatus::Tentative]))
    <div class="relative inline-block text-left" x-data="{ show: false }">
        <x-button.secondary id="calendar-download" x-bind:aria-expanded="show" aria-haspopup="true" @click="show = !show">
            <x-icon.download class="h-4 w-4 fill-current" />
        </x-button.secondary>

        <div class="absolute left-0 z-10 mt-2 w-56 origin-top-left rounded-md bg-white dark:bg-gray-900 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu" aria-orientation="vertical" aria-labelledby="calendar-download" x-cloak x-show="show"
            @click.outside="show = false" x-transition>
            <div class="py-1" role="none">
                <a href="{{ route('booking.show.ics', $booking) }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 active:bg-gray-100 active:text-gray-900 focus:bg-gray-100 focus:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white dark:active:bg-gray-700 dark:active:text-white dark:focus:bg-gray-700 dark:focus:text-white"
                    role="menuitem">{{ __('Add to Calendar') }}</a>
                <a href="{{ route('booking.email', $booking) }}"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900 active:bg-gray-100 active:text-gray-900 focus:bg-gray-100 focus:text-gray-900 dark:text-gray-200 dark:hover:bg-gray-700 dark:hover:text-white dark:active:bg-gray-700 dark:active:text-white dark:focus:bg-gray-700 dark:focus:text-white"
                    role="menuitem">{{ __('Send to Email') }}</a>
            </div>
        </div>
    </div>
@endif
