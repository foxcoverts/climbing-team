@use('App\Enums\BookingStatus')
<x-layout.app :title="__('Cancel Booking')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10"
            x-data="{
                booking: {{ Js::from([
                    'activity' => $booking->activity,
                    'start_date' => $booking->start_at,
                ]) }},
            }">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    <span x-text="booking.activity || 'Booking'">{{ $booking->activity }}</span>
                    -
                    <span x-text="dateString(booking.start_date)">&nbsp;</span>
                </h1>
                <div class="flex items-center gap-4 justify-end grow">
                    <x-badge.booking-status :status="$booking->status" />
                </div>
            </div>
        </header>

        <div class="p-4 sm:px-8 grid md:max-lg:grid-cols-booking xl:grid-cols-booking gap-4">
            <form method="post" action="{{ route('booking.update', $booking) }}" id="cancel-booking"
                class="w-full max-w-prose" x-data="{
                    submitted: false,
                    booking: {{ Js::from([
                        'reason' => old('reason'),
                    ]) }},
                }" x-on:submit="setTimeout(() => submitted = true, 0)">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="{{ BookingStatus::Cancelled->value }}" />

                <div class="space-y-6">
                    <h2
                        class="text-xl font-medium text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200 flex items-center justify-between">
                        {{ __('Cancel Booking') }}
                    </h2>

                    <div class="space-y-1 text-gray-800 dark:text-gray-200">
                        <p>{{ __('When you cancel a booking all attendees will be notified by email, and anyone who has not yet responded will be removed from the guest list. You will not be able to make any changes to the booking once it has been cancelled.') }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <x-input-label for="reason" :value="__('Why is this booking being cancelled?')" />
                        <x-text-input id="reason" name="reason" type="text" class="block w-full" x-autofocus
                            :placeholder="__('e.g., the group has postponed their camp.')" maxlength="255" required x-model='booking.reason' />
                        <x-input-error :messages="$errors->get('reason')" />
                    </div>
                </div>

                <footer class="flex flex-wrap items-start gap-4 mt-6 justify-end">
                    <x-button.danger class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Cancel Booking')"
                        x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Cancel Booking') }}'" />

                    @unless ($ajax)
                        <x-button.secondary :href="route('booking.show', $booking)" :label="__('Back')" />
                    @endunless
                </footer>
            </form>

            <div class="flex flex-col gap-4">
                <x-guest-list :$booking :$currentUser :showTools="false" />

                <x-related-bookings-list :$booking :$currentUser />
            </div>
        </div>
    </section>
</x-layout.app>
