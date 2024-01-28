<x-layout.app :title="__('Bookings')">
    <section class="sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-2 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    @each('booking.item', $bookings, 'booking', 'booking.empty')
                </div>

                <x-admin.footer>
                    <x-button.primary :href="route('booking.create')">
                        {{ __('Add Booking') }}
                    </x-button.primary>
                </x-admin.footer>
            </div>
        </div>
    </section>
</x-layout.app>
