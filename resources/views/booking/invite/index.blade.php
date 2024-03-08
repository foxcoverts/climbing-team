<x-layout.app :title="__('Booking Invites')">
    <section class="p-4 sm:px-8 grow">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Booking Invites')
            </h2>
        </header>
        @include('booking.partials.table-list', [
            'showRoute' => 'booking.attendance.show',
        ])
    </section>
</x-layout.app>
