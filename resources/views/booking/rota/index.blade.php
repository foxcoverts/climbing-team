<x-layout.app :title="__('My Rota')">
    <section class="p-4 sm:px-8 grow">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('My Rota')
            </h2>
        </header>
        @include('booking.partials.table-list', [
            'showRoute' => 'booking.show',
        ])
    </section>
</x-layout.app>
