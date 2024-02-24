@use(App\Models\User)
<x-layout.app :title="__('Users')">
    <section class="p-4 sm:p-8">
        <header>
            <h2 class="text-3xl font-medium text-gray-900 dark:text-gray-100">
                {{ __('Users') }}
            </h2>
        </header>

        <div class="grid grid-cols-1 divide-y divide-gray-200 border-y border-gray-200 my-2">
            @each('user.partials.item', $users, 'user', 'user.partials.empty')
        </div>

        <x-admin.footer>
            @can('create', User::class)
                <x-button.primary :href="route('user.create')">
                    {{ __('Register') }}
                </x-button.primary>
            @endcan
        </x-admin.footer>
    </section>
</x-layout.app>
