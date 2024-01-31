<x-layout.app :title="__('Bookings')">
    <section class="sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-2 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    @each('trash.booking.item', $bookings, 'booking', 'trash.booking.empty')
                </div>
            </div>
        </div>
    </section>
</x-layout.app>
