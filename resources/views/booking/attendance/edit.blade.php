@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Edit Attendance')">
    <section class="p-4 sm:p-8 max-w-xl">
        @include('booking.partials.header', ['booking' => $booking])
        @include('booking.partials.details', ['booking' => $booking])

        <div class="flex items-center gap-4">
            @include('booking.partials.respond-button', [
                'booking' => $booking,
                'attendance' => $attendance,
            ])
            <x-button.secondary :href="route('booking.invite')">
                {{ __('Back') }}
            </x-button.secondary>
        </div>
    </section>
</x-layout.app>
