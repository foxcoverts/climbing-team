<x-app-layout :title="__('Dashboard')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="py-2 bg-white dark:bg-gray-700 shadow sm:rounded-lg">
                <div class="grid grid-cols-1 divide-y divide-gray-700 dark:divide-gray-300">
                    <x-admin.link href="{{ route('event.index') }}">{{ __('Events') }}</x-admin.link>
                    <x-admin.link href="{{ route('user.index') }}">{{ __('Users') }}</x-admin.link>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>