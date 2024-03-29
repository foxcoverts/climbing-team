<x-layout.app :title="$user->name">
    <section class="p-4 sm:px-8 max-w-xl space-y-4">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium">{{ $user->name }}</h2>
        </header>

        <div class="space-y-4 max-w-xl flex-grow">
            <p class="flex flex-wrap gap-2 items-center mb-4">
                @unless ($user->isActive())
                    <x-badge.active :active="false" class="text-sm" />
                @endunless
                <x-badge.role :role="$user->role" class="text-sm" />
                @if ($user->isUnder18())
                    <x-badge color="pink" class="text-sm" :label="__('Under 18')" />
                @endif
                @foreach ($user->accreditations as $accreditation)
                    <x-badge.accreditation :accreditation="$accreditation" class="text-sm" />
                @endforeach
            </p>

            <div>
                <h3 class="text-xl font-medium mb-2">@lang('Contact')</h3>
                <div class="space-y-2">
                    <div>
                        <x-fake-label :value="__('Email')" />
                        <p>
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
                    </div>

                    @if ($user->phone)
                        <div>
                            <x-fake-label :value="__('Phone')" />
                            <p><a class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                    href="tel:{{ $user->phone?->formatForMobileDialingInCountry('GB') }}">{{ $user->phone?->formatForCountry('GB') }}</a>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            @if ($user->emergency_name && $user->emergency_phone)
                <div>
                    <h3 class="text-xl font-medium mb-2">@lang('Emergency Contact')</h3>
                    <div class="space-y-2">
                        <div>
                            <x-fake-label :value="__('Name')" />
                            <p>{{ $user->emergency_name }}</p>
                        </div>

                        <div>
                            <x-fake-label :value="__('Phone')" />
                            <p><a class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                    href="tel:{{ $user->emergency_phone?->formatForMobileDialingInCountry('GB') }}">{{ $user->emergency_phone?->formatForCountry('GB') }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <div>
                <h3 class="text-xl font-medium mb-2">@lang('Settings')</h3>
                <div class="space-y-2">
                    <div>
                        <x-fake-label :value="__('Section')" />
                        <p>@lang('app.user.section.' . $user->section->value)</p>
                    </div>

                    <div>
                        <x-fake-label :value="__('Timezone')" />
                        <p>{{ $user->timezone }}</p>
                    </div>
                </div>
            </div>
        </div>

        <footer class="flex items-start gap-4 mt-6">
            @can('update', $user)
                <x-button.primary :href="route('user.edit', $user)">
                    @lang('Edit')
                </x-button.primary>
            @endcan
            @if (!$user->isActive())
                @can('update', $user)
                    <form method="post" action="{{ route('user.invite', $user) }}" x-data="{ submitted: false }"
                        x-on:submit="setTimeout(() => submitted = true, 0)">
                        @csrf
                        <x-button.secondary type="submit" x-bind:disabled="submitted"
                            x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Re-send Invite') }}'" />
                    </form>
                @endcan
            @endif
            @can('manage', App\Models\Booking::class)
                <x-button.secondary :href="route('user.booking.index', $user)">
                    @lang('Bookings')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
