<x-layout.app :title="__('Users')">
    <section class="sm:py-8 lg:py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-2 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y divide-gray-200">
                    @each('user.item', $users, 'user', 'user.empty')
                </div>

                <x-admin.footer>
                    <x-admin.button :href="route('user.create')" style='primary'>
                        {{ __('Register') }}
                    </x-admin.button>
                </x-admin.footer>
            </div>
        </div>
    </section>
</x-layout.app>
