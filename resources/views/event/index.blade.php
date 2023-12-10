<x-app-layout :title="__('Events')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Events') }}
        </h2>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-2 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    @each('event.item', $events, 'event', 'event.empty')
                </div>

                <x-admin.footer>
                    <x-admin.button :href="route('event.create')" style='primary'>
                        {{ __('Add Event') }}
                    </x-admin.button>
                </x-admin.footer>
            </div>
        </div>
    </section>
</x-app-layout>
