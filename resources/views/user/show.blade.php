<x-layout.app :title="$user->name">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div
                class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg space-y-4">
                <section>
                    <header>
                        <h2 class="text-3xl font-medium">
                            {{ $user->name }}
                        </h2>
                    </header>

                    <div class="space-y-1 my-2 max-w-xl flex-grow">
                        <p>
                            <dfn class="not-italic font-bold after:content-[':']">{{ __('Email') }}</dfn>
                            <a href="mailto:{{ $user->email }}"
                                class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ $user->email }}</a>

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <span class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                    {{ __('This email address is unverified.') }}
                                </span>
                            @endif
                        </p>

                        <p>
                            <dfn class="not-italic font-bold after:content-[':']">{{ __('Timezone') }}</dfn>
                            {{ $user->timezone }}
                        </p>
                    </div>

                    <footer class="flex items-center gap-4 mt-2">
                        <x-button.primary href="{{ route('user.edit', $user) }}">
                            {{ __('Edit') }}
                        </x-button.primary>
                        @include('user.partials.delete-button')
                    </footer>
                </section>
            </div>
        </div>
    </div>
</x-layout.app>
