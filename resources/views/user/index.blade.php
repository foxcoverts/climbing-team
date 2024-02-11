<x-layout.app :title="__('Users')">
    <section class="sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-3xl font-medium text-gray-900 dark:text-gray-100">
                        {{ __('Users') }}
                    </h2>
                </header>

                <div class="grid grid-cols-1 divide-y divide-gray-200 border-y border-gray-200 my-2">
                    @each('user.partials.item', $users, 'user', 'user.partials.empty')
                </div>

                <x-admin.footer>
                    <x-button.primary :href="route('user.create')">
                        {{ __('Register') }}
                    </x-button.primary>
                </x-admin.footer>
            </div>
        </div>
    </section>
</x-layout.app>
