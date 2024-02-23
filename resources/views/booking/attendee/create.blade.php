@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Add Attendee')">
    <section class="p-4 sm:p-8 max-w-xl">
        @include('booking.partials.header', ['booking' => $booking])
        <div class="space-y-1 mt-2 max-w-xl flex-grow">
            <p class="text-lg text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200">
                {{ $booking->location }}</p>

            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('When') }}</dfn>
                @if ($booking->start_at->isSameDay($booking->end_at))
                    {{ __(':start_date from :start_time to :end_time', [
                        'start_date' => $booking->start_at->toFormattedDayDateString(),
                        'start_time' => $booking->start_at->format('H:i'),
                        'end_time' => $booking->end_at->format('H:i'),
                    ]) }}
                @else
                    {{ __(':start to :end', [
                        'start' => $booking->start_at->toDayDateTimeString(),
                        'end' => $booking->end_at->toDayDateTimeString(),
                    ]) }}
                @endif
            </p>
            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Duration') }}</dfn>
                {{ $booking->start_at->diffAsCarbonInterval($booking->end_at) }}</p>
        </div>

        <form method="post" action="{{ route('booking.attendee.store', $booking) }}" class="space-y-1">
            @csrf

            <h3 class="text-xl font-medium">{{ __('Attendance') }}</h3>

            <div>
                <x-input-label for="user_id" :value="__('Attendee')" />
                <x-select-input id="user_id" name="user_id" class="mt-1 block" required :value="old('user_id')">
                    <option value="" disabled selected>{{ __('-- Select User --') }}</option>
                    <x-select-input.collection :options="$users" label_key="name" />
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
            </div>

            <div>
                <x-input-label for="status" :value="__('Status')" />
                <x-select-input id="status" name="status" class="mt-1 block" required :value="old('status', AttendeeStatus::NeedsAction)">
                    <x-select-input.enum :options="AttendeeStatus::class" lang="app.attendee.status.:value" />
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('status')" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <x-button.primary>
                    {{ __('Add Attendee') }}
                </x-button.primary>

                <x-button.secondary :href="route('booking.show', $booking)">
                    {{ __('Back') }}
                </x-button.secondary>
            </div>
        </form>
    </section>
</x-layout.app>
