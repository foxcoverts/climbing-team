<x-layout.app :title="$user->name">
    <section class="p-4 sm:p-8 max-w-xl space-y-4">
        <header>
            <h2 class="text-3xl font-medium">{{ $user->name }}</h2>
        </header>

        <div class="space-y-2 max-w-xl flex-grow">
            <p class="flex space-x-1 items-center">
                <x-badge.role :role="$user->role" class="text-sm" />
                @foreach ($user->accreditations as $accreditation)
                    <x-badge.accreditation :accreditation="$accreditation" class="text-sm" />
                @endforeach
            </p>

            <p>
                <dfn class="not-italic font-bold after:content-[':']">{{ __('Email') }}</dfn>
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
                    @if ($user->hasVerifiedEmail())
                        <a href="mailto:{{ $user->email }}"
                            class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ $user->email }}</a>
                        <x-badge color="lime" class="text-xs">
                            {{ __('Verified') }}
                        </x-badge>
                    @else
                        <span>{{ $user->email }}</span>
                        <x-badge color="pink" class="text-xs">
                            {{ __('Unverified') }}
                        </x-badge>
                    @endif
                @endif
            </p>

            <p>
                <dfn class="not-italic font-bold after:content-[':']">{{ __('Timezone') }}</dfn>
                {{ $user->timezone }}
            </p>
        </div>

        <footer class="flex items-center gap-4">
            @can('update', $user)
                <x-button.primary href="{{ route('user.edit', $user) }}">
                    {{ __('Edit') }}
                </x-button.primary>
            @endcan
            @include('user.partials.delete-button')
            <x-button.secondary href="{{ route('user.index') }}">
                {{ __('Back') }}
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
