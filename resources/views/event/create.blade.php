<x-app-layout :title="__('Events')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Event') }}
        </h2>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-2 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    <p class="p-4 py-2 text-left text-m leading-5 text-gray-700 dark:text-gray-300">
                        TODO
                    </p>
                </div>

                <footer class="flex px-2 border-t border-t-gray-200">
                    <x-admin.button>
                        {{ __('Add Event') }}
                    </x-admin.button>
                    <x-admin.button-link :href="route('event.index')">
                        {{ __('Cancel') }}
                    </x-admin.button-link>
                </footer>
            </div>
        </div>
    </section>
</x-app-layout>