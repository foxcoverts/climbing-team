<x-layout.app :title="__('booking.title')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header class="border-b border-gray-900 dark:border-white">
                            <h2 class="text-3xl font-medium">
                                {{ $booking->activity }} - {{ $booking->start_at->format('D j M') }}
                            </h2>
                            <p class="text-lg text-gray-800 dark:text-gray-200">{{ $booking->location }}</p>
                        </header>

                        <div class="space-y-2 my-2">
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
                            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Status') }}</dfn>
                                {{ __($booking->status->name) }}</p>
                            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Location') }}</dfn>
                                {{ $booking->location }}</p>
                            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Group Name') }}</dfn>
                                {{ $booking->group_name }}</p>
                            @if (!empty($booking->notes))
                                <div><dfn class="not-italic font-bold after:content-[':']">{{ __('Notes') }}</dfn>
                                    <x-markdown :text="$booking->notes" />
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <form method="POST" action="{{ route('trash.booking.show', $booking) }}">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="deleted_at" value="0" />
                                <x-button.primary>
                                    {{ __('Restore') }}
                                </x-button.primary>
                            </form>
                            @include('trash.booking.partials.delete-button')
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
