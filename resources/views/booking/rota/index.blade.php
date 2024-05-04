<x-layout.app :title="__('My Rota')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.inbox.check style="height: .75lh" class="fill-current" aria-hidden="true" />
                    <span class="grow">{{ __('My Rota') }}</span>
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
