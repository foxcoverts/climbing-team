@use('App\Enums\BookingStatus')
@use('Carbon\Carbon')
@props(['bookings', 'showRoute' => 'booking.show', 'toolsView' => null])
<div class="w-full text-gray-700 dark:text-gray-300 space-y-4">
    @forelse ($bookings as $day => $list)
        <div class="border-x border-gray-300">
            <div
                class="px-3 py-2 font-medium text-left text-nowrap sticky -top-px sm:top-16 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300 border-y border-gray-300">
                <span x-data="{{ Js::from(['date' => localDate($day)]) }}" x-text="dateString(date)">&nbsp;</span>
            </div>
            @foreach ($list as $booking)
                <div
                    class="text-left p-0 group border-b border-gray-300 hover:bg-opacity-15 hover:bg-gray-900 hover:text-black dark:text-gray-100 dark:hover:bg-opacity-15 dark:hover:bg-white dark:hover:text-white flex items-center gap-2">
                    <a href="{{ route($showRoute, $booking) }}" class="block grow px-3 py-2">
                        <span class="mr-4">
                            {{ localDate($booking->start_at, $booking->timezone)->format('H:i') }} -
                            {{ localDate($booking->end_at, $booking->timezone)->format('H:i') }}
                        </span>
                        <span class="group-hover:underline">
                            {{ __(':activity for :group at :location', [
                                'activity' => $booking->activity,
                                'group' => $booking->group_name,
                                'location' => $booking->location,
                            ]) }}
                        </span>
                        @unless ($booking->status == BookingStatus::Confirmed)
                            <x-badge.booking-status :status="$booking->status" class="text-xs align-middle" />
                        @endunless
                        @php($attendee = $booking->attendees->find($user))
                        @if ($attendee?->attendance?->status)
                            <x-badge.attendee-status :status="$attendee->attendance->status" class="text-xs align-middle" />
                        @endif
                    </a>
                    @isset($toolsView)
                        @include($toolsView)
                    @endisset
                </div>
            @endforeach
        </div>
    @empty
        <div class="border border-gray-300 divide-y divide-gray-300">
            <div class="text-left px-3 py-2">
                {{ __('No bookings to display.') }}
            </div>
        </div>
    @endforelse
</div>
