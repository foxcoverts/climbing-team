<x-layout.app :header="$user->name" :title="__('Update - :name', $user->only('name'))">
    <section class="sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-2 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    <p class="p-4 py-2 text-left text-m leading-5 text-gray-700 dark:text-gray-300">
                        TODO
                    </p>
                </div>

                <x-admin.footer>
                    <x-button.primary>
                        {{ __('Update') }}
                    </x-button.primary>
                    <x-button.secondary :href="route('user.show', $user)">
                        {{ __('Cancel') }}
                    </x-button.secondary>
                </x-admin.footer>
            </div>
        </div>
    </section>
</x-layout.app>
