@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Edit Attendance')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header', ['booking' => $booking])
        @include('booking.partials.details', ['booking' => $booking])

        <footer class="flex items-center gap-4 mt-2">
            @include('booking.partials.respond-button', [
                'booking' => $booking,
                'attendance' => $attendance,
            ])
            <x-button.secondary :href="route('booking.invite')">
                {{ __('Back') }}
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
