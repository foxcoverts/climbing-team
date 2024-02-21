@use(App\Enums\BookingStatus)
<x-layout.app :title="$booking->activity . ' - ' . localDate($booking->start_at)->toFormattedDayDateString()">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header', ['booking' => $booking])

        <div class="md:flex md:space-x-4">
            @include('booking.partials.details', ['booking' => $booking])

            <aside class="my-2 flex-grow max-w-xl">
                <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200">
                    {{ __('Guest list') }}
                </h2>

                @forelse ($attendees->groupBy('attendance.status') as $status => $attendees)
                    <h3 class="text-lg">{{ __("attendee.status.$status") }}</h3>
                    <ul class="mb-3">
                        @foreach ($attendees as $attendee)
                            <li class="flex space-x-1 items-start">
                                <a href="{{ route('user.show', $attendee) }}">{{ $attendee->name }}</a>
                                <a href="{{ route('booking.attendee.show', [$booking, $attendee]) }}">
                                    <x-icon.edit-pencil class="w-3 h-3 fill-current inline-block align-text-top"
                                        title="{{ __('Edit Attendance') }}" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @empty
                    <p>No one has been invited yet.</p>
                @endforelse
            </aside>
        </div>

        <footer class="flex items-center gap-4 mt-2">
            <x-button.primary :href="route('booking.edit', $booking)">
                {{ __('Edit') }}
            </x-button.primary>
            @if ($booking->status == BookingStatus::Cancelled)
                <form method="post" action="{{ route('booking.destroy', $booking) }}">
                    @csrf
                    @method('delete')

                    <x-button.danger>
                        {{ __('Delete') }}
                    </x-button.danger>
                </form>
            @else
                <form method="post" action="{{ route('booking.update', $booking) }}">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="status" value="{{ BookingStatus::Cancelled }}" />
                    <x-button.danger>
                        {{ __('Cancel Booking') }}
                    </x-button.danger>
                </form>
            @endif
            @include('booking.partials.respond-button', [
                'booking' => $booking,
                'attendance' => $attendance,
            ])
            <x-button.secondary :href="route('booking.attendee.invite', $booking)">
                {{ __('Invite Attendee') }}
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
