<x-layout.app :title="__(':name - Bookings', ['name' => $user->name])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang(':name - Bookings', ['name' => $user->name])
                </h1>

                @can('manage', App\Models\Booking::class)
                    <nav class="flex items-center gap-4 justify-end grow">
                        <x-button.primary :href="route('user.booking.invite', $user)" :label="__('Invite')" />
                    </nav>
                @endcan
            </div>
        </header>

        <div class="p-4 sm:px-8 sm:mt-4">
            @include('booking.partials.list', [
                'showRoute' => 'booking.show',
            ])
        </div>

        @can('view', $user)
            <footer class="p-4 sm:px-8 flex flex-wrap items-center gap-4">
                <x-button.secondary :href="route('user.show', $user)">
                    @lang('Back')
                </x-button.secondary>
            </footer>
        @endcan
    </section>
</x-layout.app>
