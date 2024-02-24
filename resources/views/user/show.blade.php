<x-layout.app :title="$user->name">
    <section class="p-4 sm:p-8 max-w-xl space-y-4">
        <header>
            <h2 class="text-3xl font-medium">{{ $user->name }}</h2>
        </header>

        <div class="space-y-2 max-w-xl flex-grow">
            <p>
                <dfn class="not-italic font-bold after:content-[':']">{{ __('Role') }}</dfn>
                {{ __("app.user.role.{$user->role->value}") }}
            </p>

            <p>
                <dfn class="not-italic font-bold after:content-[':']">{{ __('Accreditations') }}</dfn>
                @forelse ($user->accreditations as $accreditation)
                    <span
                        class="inline-flex items-center rounded-md bg-gray-50 dark:bg-gray-700 text-gray-600 dark:text-gray-300 px-2 py-1 text-xs font-medium ring-1 ring-inset ring-gray-500/25 dark:ring-gray-300/25">{{ __("app.user.accreditation.$accreditation->value") }}</span>
                @empty
                    None
                @endforelse
            </p>

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

        <footer class="flex items-center gap-4">
            <x-button.primary href="{{ route('user.edit', $user) }}">
                {{ __('Edit') }}
            </x-button.primary>
            @include('user.partials.delete-button')
        </footer>
    </section>
</x-layout.app>
