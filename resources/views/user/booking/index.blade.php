<x-layout.app :title="__(':name - Bookings', ['name' => $user->name])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10 px-4 sm:px-8 ">
            <div class="py-2 flex flex-wrap min-h-16 max-w-prose items-center justify-between gap-2">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang(':name - Bookings', ['name' => $user->name])
                </h1>

                @can('manage', App\Models\Booking::class)
                    <nav class="grow flex justify-end">
                        <x-button.primary :href="route('user.booking.invite', $user)">
                            @lang('Invite')
                        </x-button.primary>
                    </nav>
                @endcan
            </div>
        </header>

        <div class="p-4 sm:px-8 sm:mt-4">
            @include('booking.partials.table-list', [
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
