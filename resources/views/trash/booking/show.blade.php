<x-layout.app :title="__('booking.title')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium">
                                {{ $booking->activity }}
                            </h2>
                        </header>

                        <div class="space-y-2 my-2">
                            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Start') }}</dfn>
                                {{ $booking->start_at }}</p>
                            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('End') }}</dfn>
                                {{ $booking->end_at }}</p>
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
