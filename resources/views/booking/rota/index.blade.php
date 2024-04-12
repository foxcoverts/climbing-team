<x-layout.app :title="__('My Rota')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <div class="px-4 sm:px-8 flex items-center justify-between">
                <h1 class="grow text-2xl font-medium py-4 text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.inbox.check style="height: .75lh" class="fill-current" aria-hidden="true" />
                    <span class="grow">@lang('My Rota')</span>
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8 sm:my-4">
            @include('booking.partials.list', [
                'showRoute' => 'booking.show',
            ])
        </div>
    </section>
</x-layout.app>
