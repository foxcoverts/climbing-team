<x-app-layout :title="__('Users')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-4 sm:py-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y">
                    @each('user.item', $users, 'user', 'user.empty')
                </div>

                <footer class="px-6 pt-2 sm:px-8 border-t border-t-black">
                    <a href="{{ route('user.create') }}">{{ __('Register') }}</a>
                </footer>
            </div>
        </div>
    </section>
</x-app-layout>