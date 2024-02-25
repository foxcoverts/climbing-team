@use('App\Enums\AttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__('Update Attendance')">
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

        <div class="space-y-1">
            <h3 class="text-xl font-medium">{{ __('Attendance') }}</h3>
            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Attendee') }}</dfn>
                {{ $attendee->name }}</p>

            @if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('update', $attendee->attendance))
                <form method="post" action="{{ route('booking.attendee.update', [$booking, $attendee]) }}"
                    id="update-attendance">
                    @csrf
                    @method('PUT')

                    <div>
                        <x-input-label for="status" :value="__('Status')"
                            class="not-italic font-bold after:content-[':']" />
                        <x-select-input id="status" name="status" class="mt-1 block" required :value="old('status', $attendee->attendance->status)">
                            <x-select-input.enum :options="AttendeeStatus::class" lang="app.attendee.status.:value" />
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('status')" />
                    </div>
                </form>
            @else
                <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Status') }}</dfn>
                    {{ __("app.attendee.status.{$attendee->attendance->status->value}") }}</p>
            @endif
        </div>

        <footer class="flex items-center gap-4 mt-4">
            @if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('update', $attendee->attendance))
                <x-button.primary form="update-attendance">
                    {{ __('Update Attendance') }}
                </x-button.primary>
            @endif
            @can('delete', $attendee->attendance)
                <form method="post" action="{{ route('booking.attendee.destroy', [$booking, $attendee]) }}">
                    @csrf
                    @method('delete')
                    <x-button.danger>
                        @if ($attendee->attendance->status == AttendeeStatus::NeedsAction)
                            {{ __('Remove Invitation') }}
                        @else
                            {{ __('Remove Attendance') }}
                        @endif
                    </x-button.danger>
                </form>
            @endcan
            <x-button.secondary :href="route('booking.show', $booking)">
                {{ __('Back') }}
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
