<x-layout.app :title="__('Deleted Bookings')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <div class="px-4 sm:px-8 flex items-center justify-between">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">
                    @lang('Deleted Bookings')
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8 sm:mt-4">
            @include('booking.partials.list', ['showRoute' => 'trash.booking.show'])
        </div>
    </section>
</x-layout.app>
