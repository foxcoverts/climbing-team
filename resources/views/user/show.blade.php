<x-layout.app :title="$user->name">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <div class="px-4 sm:px-8">
                <div class="py-2 flex flex-wrap min-h-16 max-w-prose items-center justify-between gap-2">
                    <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                        {{ $user->name }}
                    </h1>

                    @can('update', $user)
                        <nav class="grow flex justify-end">
                            <x-button.primary :href="route('user.edit', $user)">
                                @lang('Edit')
                            </x-button.primary>
                        </nav>
                    @endcan
                </div>
            </div>
        </header>

        <div class="p-4 sm:px-8">
            <div class="max-w-prose">
                <p class="flex flex-wrap gap-2 items-stretch mb-4">
                    @unless ($user->isActive())
                        <x-badge.active :active="$user->isActive()" class="text-sm text-nowrap whitespace-nowrap" />
                    @endunless
                    <x-badge.role :role="$user->role" class="text-sm text-nowrap whitespace-nowrap" />
                    @if ($user->isUnder18() || $user->isParent())
                        <x-badge.section :section="$user->section" class="text-sm text-nowrap whitespace-nowrap" />
                    @endif
                    @if ($user->isPermitHolder())
                        <a href="{{ route('user.qualification.index', $user) }}">
                            <x-badge.permit-holder class="text-sm text-nowrap whitespace-nowrap" />
                        </a>
                    @endif
                    @if ($user->isKeyHolder())
                        @can('manage', App\Models\Key::class)
                            <a href="{{ route('key.index') }}" class="flex items-stretch">
                                <x-badge.key-holder class="text-sm whitespace-nowrap" />
                            </a>
                        @else
                            <x-badge.key-holder class="text-sm whitespace-nowrap" />
                        @endcan
                    @endif
                    @foreach ($user->accreditations as $accreditation)
                        <x-badge.accreditation :accreditation="$accreditation" class="text-sm text-nowrap whitespace-nowrap" />
                    @endforeach
                </p>

                <div x-data="{
                    open: false,
                    gdprContact: $persist(false).using(sessionStorage).as('gdpr-contact-{{ $user->id }}')
                }">
                    <h3 class="text-lg sm:text-xl my-2">
                        <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                            <x-icon.cheveron.down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>@lang('Contact Details')</span>
                            @if ($user->hasVerifiedEmail())
                                <x-badge color="lime" icon="outline.checkmark" :label="__('Email Verified')" class="text-sm" />
                            @else
                                <x-badge color="pink" icon="outline.exclamation" :label="__('Email Unverified')" class="text-sm" />
                            @endif
                        </button>
                    </h3>
                    <div class="space-y-2 sm:pl-5" x-show="open" x-cloak x-transition>
                        <div class="space-y-2" :class="gdprContact && 'text-gray-600 dark:text-gray-400'">
                            <p><strong>@lang('Notice'):</strong>
                                @lang('You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.')
                            </p>
                            <p>
                                <button class="flex items-start pl-1 gap-2" @click="gdprContact = !gdprContact">
                                    <x-icon.outline class="mt-1 w-4 h-4 fill-current" x-show="!gdprContact" />
                                    <x-icon.outline.checkmark class="mt-1 w-4 h-4 fill-current" x-cloak
                                        x-show="gdprContact" />
                                    <span class="text-left">@lang('I have a legitimate reason to view these contact details')</span>
                                </button>
                            </p>
                        </div>
                        <div class="space-y-2" x-cloak x-show="gdprContact" x-transition>
                            <div>
                                <x-fake-label :value="__('Email')" />
                                <p class="text-gray-700 dark:text-gray-300">
                                    @if ($user->hasVerifiedEmail())
                                        <a href="mailto:{{ $user->email }}"
                                            class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">{{ $user->email }}</a>
                                    @else
                                        <span>{{ $user->email }}</span>
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
                        <h3 class="text-lg sm:text-xl my-2">
                            <button @click="open = !open" x-bind:aria-pressed="open"
                                class="flex items-center space-x-1">
                                <x-icon.cheveron.down aria-hidden="true"
                                    class="w-4 h-4 fill-current transition-transform" ::class="open ? '' : '-rotate-90'" />
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
                                        <x-icon.outline class="mt-1 w-4 h-4 fill-current" x-show="!gdprContact" />
                                        <x-icon.outline.checkmark class="mt-1 w-4 h-4 fill-current" x-cloak
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

                <div x-data="{ open: false }">
                    <h3 class="text-lg sm:text-xl my-2">
                        <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                            <x-icon.cheveron.down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>@lang('Kit Checks')</span>
                            @isset($user->latestKitCheck)
                                <x-badge.kit-check-expired :expired="$user->latestKitCheck->isExpired()" class="text-sm" />
                            @else
                                <x-badge color="yellow" icon="outline.exclamation" :label="__('Unknown')" class="text-sm" />
                            @endisset
                        </button>
                    </h3>
                    <div class="space-y-2 sm:pl-5" x-show="open" x-cloak x-transition>
                        @isset($user->latestKitCheck)
                            <x-fake-label :value="__('Last checked')" />
                            <div class="flex items-center gap-2">
                                @can('viewAny', App\Models\KitCheck::class)
                                    <p><a href="{{ route('kit-check.user.index', $user) }}" x-data="{{ Js::from(['checked_on' => localDate($user->latestKitCheck->checked_on)]) }}"
                                            x-text="dateString(checked_on)">
                                            {{ localDate($user->latestKitCheck->checked_on)->toFormattedDayDateString() }}
                                        </a>
                                    </p>
                                @else
                                    <p>
                                        {{ localDate($user->latestKitCheck->checked_on)->toFormattedDayDateString() }}
                                    </p>
                                @endcan
                            </div>
                        @else
                            <p>
                                <strong>@lang('This user has not checked their kit yet.')</strong>
                                @lang('If this user has any climbing kit of their own they should ask one of the team\'s kit checkers to look over it with them to ensure it is in good condition.')
                            </p>
                        @endisset
                        @can('create', App\Models\KitCheck::class)
                            <x-button.primary :href="route('kit-check.create', ['users' => $user->id])" :label="__('Log Kit Check')" />
                        @endcan
                    </div>
                </div>

                <div x-data="{ open: true }">
                    <h3 class="text-lg sm:text-xl my-2">
                        <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                            <x-icon.cheveron.down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
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

            <footer class="mt-4 flex flex-wrap items-start gap-4">
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
                            <x-button.secondary type="submit" class="whitespace-nowrap" x-bind:disabled="submitted"
                                :label="__('Re-send Invite')"
                                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Re-send Invite') }}'" />
                        </form>
                    @endcan
                @endif
            </footer>
        </div>
    </section>
</x-layout.app>
