<x-layout.app :title="__('My Rota')">
    <section class="p-4 sm:px-8 grow">
        <header>
            <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-2">
                <x-icon.inbox.check style="height: .75lh" class="fill-current" aria-hidden="true" />
                <span>@lang('My Rota')</span>
            </h2>
        </header>
        @include('booking.partials.table-list', [
            'showRoute' => 'booking.show',
        ])
    </section>
</x-layout.app>
