<x-layout.app :title="$user->name">
    <section class="p-4 sm:p-8 max-w-xl space-y-4">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium">{{ $user->name }}</h2>
        </header>

        <div class="space-y-2 max-w-xl flex-grow">
            <p class="flex flex-wrap gap-2 items-center mb-4">
                @unless ($user->isActive())
                    <x-badge.active :active="false" class="text-sm" />
                @endunless
                <x-badge.role :role="$user->role" class="text-sm" />
                @foreach ($user->accreditations as $accreditation)
                    <x-badge.accreditation :accreditation="$accreditation" class="text-sm" />
                @endforeach
            </p>

            <p>
                <dfn class="not-italic font-bold after:content-[':']">@lang('Email')</dfn>
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail)
                    @if ($user->hasVerifiedEmail())
                        <a href="mailto:{{ $user->email }}"
                            class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ $user->email }}</a>
                        <x-badge color="lime" class="text-xs">
                            @lang('Verified')
                        </x-badge>
                    @else
                        <span>{{ $user->email }}</span>
                        <x-badge color="pink" class="text-xs">
                            @lang('Unverified')
                        </x-badge>
                    @endif
                @endif
            </p>

            <p>
                <dfn class="not-italic font-bold after:content-[':']">@lang('Timezone')</dfn>
                {{ $user->timezone }}
            </p>
        </div>

        <footer class="flex items-start gap-4">
            @can('update', $user)
                <x-button.primary href="{{ route('user.edit', $user) }}">
                    @lang('Edit')
                </x-button.primary>
            @endcan
            @include('user.partials.delete-button')
            <x-button.secondary href="{{ route('user.index') }}">
                @lang('Back')
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
