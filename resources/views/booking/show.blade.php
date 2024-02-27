@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('App\Models\Attendance')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="$booking->activity . ' - ' . localDate($booking->start_at)->toFormattedDayDateString()">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header', ['booking' => $booking])

        <div class="md:flex md:space-x-4">
            @include('booking.partials.details', ['booking' => $booking])
            @include('booking.partials.guest-list', [
                'attendees' => $attendees,
                'booking' => $booking,
            ])
        </div>

        <footer class="flex items-center gap-4 mt-2">
            @can('update', $booking)
                <x-button.primary :href="route('booking.edit', $booking)">
                    {{ __('Edit') }}
                </x-button.primary>
            @endcan
            @include('booking.partials.respond-button', [
                'booking' => $booking,
                'attendance' => $attendance,
            ])
            @if (
                $booking->isFuture() &&
                    !$booking->isCancelled() &&
                    app(Gate::class)->check('create', [Attendance::class, $booking]))
                <x-button.secondary :href="route('booking.attendee.invite', $booking)">
                    {{ __('Invite Attendee') }}
                </x-button.secondary>
            @endif
        </footer>
    </section>
</x-layout.app>
