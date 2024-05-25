<x-layout.app>
    <x-slot:title>
        {{ __('Add Related Booking') }} -
        {{ $booking->activity }} on
        {{ localDate($booking->start_at, $booking->timezone)->toFormattedDayDateString() }}
    </x-slot:title>

    <section>
        @include('booking.partials.header')

        <form method="post" action="{{ route('booking.related.store', $booking) }}" x-data="{
            booking: {{ Js::from([
                'related_id' => old('related_id'),
                'mutual' => old('mutual', true),
            ]) }},
            submitted: false,
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)" class="p-4 sm:px-8">
            @csrf

            <div class="space-y-6 max-w-prose">
                <div class="border-b border-gray-800 dark:border-gray-200">
                    <h2 class="text-xl font-medium text-gray-800 dark:text-gray-200">{{ __('Add Related Booking') }}</h2>
                </div>

                <div class="space-y-1">
                    <x-input-label for="related_id" :value="__('Related Booking')" />
                    <x-select-input id="related_id" name="related_id" required x-model="booking.related_id"
                        class="w-full overflow-ellipsis">
                        <template x-if="!booking.related_id">
                            <option value="" disabled selected></option>
                        </template>
                        @foreach ($bookings as $relatedBooking)
                            <option value="{{ $relatedBooking->id }}" @disabled($booking->related->contains($relatedBooking))>
                                {{ localDate($relatedBooking->start_at, $relatedBooking->timezone)->toFormattedDayDateString() }}
                                - {{ $relatedBooking->activity }}
                                for {{ $relatedBooking->group_name }}
                            </option>
                        @endforeach
                    </x-select-input>
                    <x-input-error :messages="$errors->get('related_id')" />
                </div>
            </div>

            <footer class="mt-6 flex gap-4">
                <x-button.primary :label="__('Add Booking')" x-bind:disabled="!booking.related_id" />
                <x-button.secondary :href="route('booking.related.index', $booking)" :label="__('Back')" />
            </footer>
        </form>
    </section>
</x-layout.app>
