<x-app-layout :title="$user->name">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Profile') }}
        </h2>
    </x-slot>

    <section class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header class="px-6 py-4">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {{ $user->name }}
                    </h2>
                </header>

                <div class="px-6 py-4">
                    <div>
                        <p class="mt-2 text-gray-800 dark:text-gray-200">
                            {{ __('Email') }}:
                            <a href="mailto:{{ $user->email}}" class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ $user->email }}</a>
                        </p>

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                        <div>
                            <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                {{ __('This email address is unverified.') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <footer class="px-4 border-t border-t-black">
                    <a href="{{ route('user.edit', $user) }}" class="p-2">{{ __('Edit') }}</a>
                    <button class="p-2">{{ __('Delete') }}</button>
                </footer>
            </div>
        </div>
    </section>
</x-app-layout>