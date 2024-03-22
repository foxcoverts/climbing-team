<x-layout.app :title="__('My Invites')">
    <section class="p-4 sm:px-8 grow">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                <x-icon.inbox style="height: .75lh" class="fill-current" aria-hidden="true" />
                <span>@lang('My Invites')</span>
            </h2>
        </header>
        @include('booking.partials.table-list', [
            'showRoute' => 'booking.attendance.edit',
        ])
    </section>
</x-layout.app>
