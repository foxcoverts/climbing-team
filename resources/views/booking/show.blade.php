<x-layout.app :title="__('booking.title')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        @include('booking/partials/details', ['booking' => $booking])

                        <div class="mt-6 flex items-center gap-4">
                            <x-button.primary :href="route('booking.edit', $booking)">
                                {{ __('Edit') }}
                            </x-button.primary>
                            <form method="post" action="{{ route('booking.destroy', $booking) }}">
                                @csrf
                                @method('delete')

                                <x-button.danger>
                                    {{ __('Delete') }}
                                </x-button.danger>
                            </form>
                            @include('booking.partials.respond-button', [
                                'booking' => $booking,
                                'attendance' => $attendance,
                            ])
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
