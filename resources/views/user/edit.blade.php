<x-app-layout :title="__('Edit User')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User') }}
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

                <x-admin.footer>
                    <x-admin.button style='primary'>
                        {{ __('Update') }}
                    </x-admin.button>
                    <x-admin.button :href="route('user.show', $user)">
                        {{ __('Cancel') }}
                    </x-admin.button>
                </x-admin.footer>
            </div>
        </div>
    </section>
</x-app-layout>
