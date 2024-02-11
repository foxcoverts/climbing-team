@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('attendee.title')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        @include('booking.partials.header', ['booking' => $booking])
                        <div class="space-y-1 my-2 max-w-xl flex-grow">
                            <p
                                class="text-lg text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200">
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

                            <h3 class="text-xl font-medium">{{ __('Attendance') }}</h3>
                            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('attendee.title') }}</dfn>
                                {{ $attendee->name }}</p>
                            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Status') }}</dfn>
                                {{ __('attendee.status.' . $attendee->attendance->status->value) }}</p>
                        </div>


                        <footer class="flex items-center gap-4 my-2">
                            <x-button.primary :href="route('booking.attendee.edit', [$booking, $attendee])">
                                {{ __('Change') }}
                            </x-button.primary>
                            <form method="post"
                                action="{{ route('booking.attendee.destroy', [$booking, $attendee]) }}">
                                @csrf
                                @method('delete')

                                <x-button.danger>
                                    {{ __('Delete') }}
                                </x-button.danger>
                            </form>
                        </footer>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
