@use('App\Enums\BookingAttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__('Edit Attendance')">
    <section>
        @include('booking.partials.header')
        <div class="p-4 sm:px-8 grid md:max-lg:grid-cols-booking xl:grid-cols-booking gap-4">
            <div class="w-full max-w-prose">
                @include('booking.partials.details')

                @if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('respond', [$booking, $currentUser]))
                    <form method="post" action="{{ route('booking.attendance.update', $booking) }}"
                        x-data="{ submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                        @csrf
                        @method('PUT')
                        <div
                            class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
                            <p class="text-lg text-center">{{ __('Can you attend this event?') }}</p>
                            <div class="flex justify-center gap-4">
                                <x-button
                                    color="{{ $attendance?->status == BookingAttendeeStatus::Accepted ? 'primary' : 'secondary' }}"
                                    type="submit" name="status" x-bind:disabled="submitted" :value="BookingAttendeeStatus::Accepted->value"
                                    :label="__('Yes')" />
                                <x-button
                                    color="{{ $attendance?->status == BookingAttendeeStatus::Declined ? 'danger' : 'secondary' }}"
                                    type="submit" name="status" x-bind:disabled="submitted" :value="BookingAttendeeStatus::Declined->value"
                                    :label="__('No')" />
                                <x-button
                                    color="{{ $attendance?->status == BookingAttendeeStatus::Tentative ? 'primary' : 'secondary' }}"
                                    type="submit" name="status" x-bind:disabled="submitted" :value="BookingAttendeeStatus::Tentative->value"
                                    :label="__('Maybe')" />
                            </div>
                            <p class="text-sm text-center">
                                {{ __('Replying for :name.', ['name' => $currentUser->name]) }}
                            </p>
                        </div>
                    </form>
                @endif
            </div>

            <div class="flex flex-col gap-4">
                <x-guest-list :$booking :$currentUser :showTools="false" />

                <x-related-bookings-list :$booking :$currentUser />
            </div>
        </div>
    </section>
</x-layout.app>
