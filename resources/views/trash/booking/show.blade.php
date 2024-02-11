<x-layout.app :title="__('booking.title')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        @include('booking/partials/details', ['booking' => $booking])

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
