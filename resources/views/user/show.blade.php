<x-layout.app :title="$user->name">
    <section class="p-4 sm:px-8 max-w-xl space-y-4">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium">{{ $user->name }}</h2>
        </header>

        <div class="max-w-xl flex-grow">
            <p class="flex flex-wrap gap-2 items-center mb-4">
                @unless ($user->isActive())
                    <x-badge.active :active="false" class="text-sm" />
                @endunless
                <x-badge.role :role="$user->role" class="text-sm" />
                @if ($user->isUnder18())
                    <x-badge color="pink" class="text-sm" :label="__('Under 18')" />
                @endif
                @if ($user->isPermitHolder())
                    <x-badge.permit-holder class="text-sm" />
                @endif
                @foreach ($user->accreditations as $accreditation)
                    <x-badge.accreditation :accreditation="$accreditation" class="text-sm" />
                @endforeach
            </p>

            <div x-data="{
                open: false,
                gdprContact: $persist(false).using(sessionStorage).as('gdpr-contact-{{ $user->id }}')
            }">
                <h3 class="text-lg sm:text-xl my-2 flex items-center space-x-1">
                    <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                        <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                            ::class="open ? '' : '-rotate-90'" />
                        <span>@lang('Contact Details')</span>
                    </button>
                </h3>
                <div class="space-y-2 sm:pl-5" x-show="open" x-cloak x-transition>
                    <div class="space-y-2" :class="gdprContact && 'text-gray-600 dark:text-gray-400'">
                        <p><strong>@lang('Notice'):</strong>
                            @lang('You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.')
                        </p>
                        <p>
                            <button class="flex items-start pl-1 gap-2" @click="gdprContact = !gdprContact">
                                <x-icon.empty-outline class="mt-1 w-4 h-4 fill-current" x-show="!gdprContact" />
                                <x-icon.checkmark-outline class="mt-1 w-4 h-4 fill-current" x-cloak
                                    x-show="gdprContact" />
                                <span class="text-left">@lang('I have a legitimate reason to view these contact details')</span>
                            </button>
                        </p>
                    </div>
                    <div class="space-y-2" x-cloak x-show="gdprContact" x-transition>
                        <div>
                            <x-fake-label :value="__('Email')" />
                            <p class="text-gray-700 dark:text-gray-300">
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
                                <p class="text-gray-700 dark:text-gray-300"><a
                                        class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                        href="tel:{{ $user->phone?->formatForMobileDialingInCountry('GB') }}">{{ $user->phone?->formatForCountry('GB') }}</a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if ($user->emergency_name && $user->emergency_phone)
                <div x-data="{
                    open: false,
                    gdprContact: $persist(false).using(sessionStorage).as('gdpr-emergency-contact-{{ $user->id }}')
                }">
                    <h3 class="text-lg sm:text-xl my-2 flex items-center space-x-1">
                        <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                            <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>@lang('Emergency Contact')</span>
                        </button>
                    </h3>
                    <div class="space-y-4 sm:pl-5" x-cloak x-show="open" x-transition>
                        <div class="space-y-2" :class="gdprContact && 'text-gray-600 dark:text-gray-400'">
                            <p><strong>@lang('Notice'):</strong>
                                @lang('You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.')
                            </p>
                            <p>
                                <button class="flex items-start pl-1 gap-2" @click="gdprContact = !gdprContact">
                                    <x-icon.empty-outline class="mt-1 w-4 h-4 fill-current" x-show="!gdprContact" />
                                    <x-icon.checkmark-outline class="mt-1 w-4 h-4 fill-current" x-cloak
                                        x-show="gdprContact" />
                                    <span class="text-left">@lang('I have a legitimate reason to view these contact details')</span>
                                </button>
                            </p>
                        </div>
                        <div class="space-y-2" x-cloak x-show="gdprContact" x-transition>
                            <div>
                                <x-fake-label :value="__('Name')" />
                                <p class="text-gray-700 dark:text-gray-300">{{ $user->emergency_name }}</p>
                            </div>

                            <div>
                                <x-fake-label :value="__('Phone')" />
                                <p class="text-gray-700 dark:text-gray-300"><a
                                        class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                        href="tel:{{ $user->emergency_phone?->formatForMobileDialingInCountry('GB') }}">{{ $user->emergency_phone?->formatForCountry('GB') }}</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div x-data="{ open: true }">
                <h3 class="text-lg sm:text-xl my-2 flex items-center space-x-1">
                    <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                        <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                            ::class="open ? '' : '-rotate-90'" />
                        <span>@lang('Settings')</span>
                    </button>
                </h3>
                <div class="space-y-2 sm:pl-5" x-show="open" x-transition>
                    <div>
                        <x-fake-label :value="__('Section')" />
                        <p class="text-gray-700 dark:text-gray-300">@lang('app.user.section.' . $user->section->value)</p>
                    </div>

                    <div>
                        <x-fake-label :value="__('Timezone')" />
                        <p class="text-gray-700 dark:text-gray-300">{{ $user->timezone }}</p>
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
            @can('manage', \App\Models\Booking::class)
                <x-button.secondary :href="route('user.booking.index', $user)">
                    @lang('Bookings')
                </x-button.secondary>
            @endcan
            @can('viewAny', [\App\Models\Qualification::class, $user])
                <x-button.secondary :href="route('user.qualification.index', $user)">
                    @lang('Qualifications')
                </x-button.secondary>
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
        </footer>
    </section>
</x-layout.app>
