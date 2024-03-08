<x-layout.app :title="__('Deleted Bookings')">
    <section class="p-4 sm:px-8 grow">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Deleted Bookings')
            </h2>
        </header>
        @include('booking.partials.table-list', ['showRoute' => 'trash.booking.show'])
    </section>
</x-layout.app>
