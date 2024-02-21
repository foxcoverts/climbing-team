@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Edit Attendance')">
    <section class="p-4 sm:p-8 max-w-xl">
        <header class="border-b border-gray-800 dark:border-gray-200">
            <h2 class="text-3xl font-medium">
                {{ $booking->activity }} - {{ $booking->start_at->format('D j M') }}
            </h2>
            <p class="text-lg text-gray-800 dark:text-gray-200">{{ $booking->location }}</p>
        </header>

        <div class="space-y-1 my-2">
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

        <form method="post" action="{{ route('booking.attendance.update', $booking) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <x-input-label for="status" :value="__('Attendance')" />
                <x-select-input id="status" name="status" class="mt-1 block" required :value="old('status', $status)">
                    <option value="" disabled selected>{{ __('-- Select Status --') }}</option>
                    <x-select-input.enum :options="AttendeeStatus::class" :except="[AttendeeStatus::NeedsAction]" lang="attendee.status.:value" />
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('status')" />
            </div>

            <div class="flex items-center gap-4">
                <x-button.primary>
                    {{ __('Update') }}
                </x-button.primary>

                <x-button.secondary :href="route('booking.show', $booking)">
                    {{ __('Back') }}
                </x-button.secondary>
            </div>
        </form>
    </section>
</x-layout.app>
