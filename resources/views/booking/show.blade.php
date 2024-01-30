<x-layout.app :title="__('Booking')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium">
                                {{ __('Booking') }}
                            </h2>
                        </header>

                        <div class="space-y-2 my-2">
                            <p><strong>{{ __('Start') }}:</strong> {{ $booking->start_at }}</p>
                            <p><strong>{{ __('End') }}:</strong> {{ $booking->end_at }}</p>
                            <p><strong>{{ __('Location') }}:</strong> {{ $booking->location }}</p>
                            <p><strong>{{ __('Group Name') }}:</strong> {{ $booking->group_name }}</p>
                            @if (!empty($booking->notes))
                                <p><strong>{{ __('Notes') }}:</strong> {{ $booking->notes }}</p>
                            @endif
                        </div>

                        <div class="mt-6 flex items-center gap-4">
                            <x-button.primary :href="route('booking.edit', $booking)">
                                {{ __('Edit') }}
                            </x-button.primary>
                            <x-button.danger>
                                {{ __('Delete') }}
                            </x-button.danger>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
