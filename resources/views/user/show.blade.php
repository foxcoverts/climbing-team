<x-layout.app :title="$user->name">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ $user->name }}
                            </h2>
                        </header>

                        <div class="mt-6 space-y-6">
                            <div>
                                <p class="mt-2 text-gray-800 dark:text-gray-200">
                                    {{ __('Email') }}:
                                    <a href="mailto:{{ $user->email }}"
                                        class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ $user->email }}</a>
                                </p>

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div>
                                        <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                            {{ __('This email address is unverified.') }}
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center gap-4">
                                <x-button.primary href="{{ route('user.edit', $user) }}">
                                    {{ __('Edit') }}
                                </x-button.primary>
                                @include('user.partials.delete-button')
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layout.app>
