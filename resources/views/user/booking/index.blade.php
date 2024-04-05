<x-layout.app :title="__(':name - Bookings', ['name' => $user->name])">
    <section class="p-4 sm:px-8 grow space-y-6">
        <header>
            <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center space-x-1">
                <span>@lang(':name - Bookings', ['name' => $user->name])</span>
            </h2>
        </header>

        @include('booking.partials.table-list', [
            'showRoute' => 'booking.show',
        ])

        <footer class="flex flex-wrap items-center gap-4">
            @can('manage', App\Models\Booking::class)
                <x-button.primary :href="route('user.booking.invite', $user)">
                    @lang('Invite to Bookings')
                </x-button.primary>
            @endcan
            @can('view', $user)
                <x-button.secondary :href="route('user.show', $user)">
                    @lang('Back')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
