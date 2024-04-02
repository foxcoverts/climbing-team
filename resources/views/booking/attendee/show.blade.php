@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('Carbon\Carbon')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__(':name - Attendance', ['name' => $attendee->name])">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap-reverse gap-4">
            @include('booking.partials.details')

            <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                <div class="space-y-1">
                    <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        {{ $attendee->name }}</h2>

                    <div class="flex flex-wrap items-center gap-1">
                        @if ($attendee->isPermitHolder())
                            <x-badge.permit-holder class="text-sm" />
                        @endif

                        @if ($attendee->isGuest())
                            <x-badge.role :role="Role::Guest" class="text-sm" />
                        @endif

                        @if ($attendee->isUnder18())
                            <x-badge.under-18 class="text-sm" />
                        @endif
                    </div>

                    @if ($attendee->isPermitHolder())
                        @can('viewAny', [App\Models\Qualification::class, $attendee])
                            <div x-data="{ open: false }">
                                <h3 class="text-lg my-2 flex items-center gap-1">
                                    <button @click="open = !open" x-bind:aria-pressed="open"
                                        class="flex items-center space-x-1">
                                        <x-icon.cheveron-down aria-hidden="true"
                                            class="w-4 h-4 fill-current transition-transform" ::class="open ? '' : '-rotate-90'" />
                                        <span>@lang('Qualifications')</span>
                                    </button>
                                </h3>
                                <div class="mb-3" x-cloak x-show="open" x-transition>
                                    <div
                                        class="text-gray-700 dark:text-gray-300 divide-y divide-gray-600 dark:divide-gray-400">
                                        @forelse ($attendee->qualifications as $qualification)
                                            <div class="pl-5 py-2 first:pt-0 last:pb-0">
                                                <div class="list-item list-disc">
                                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $qualification->detail->summary }}</h4>
                                                    @if ($qualification->detail instanceof \App\Models\ScoutPermit)
                                                        <p><dfn
                                                                class="not-italic font-medium text-gray-900 dark:text-gray-100">@lang('Restrictions'):</dfn>
                                                            {{ $qualification->detail->restrictions }}
                                                        </p>
                                                    @endif
                                                    @if ($qualification->expires_on)
                                                        <p><dfn
                                                                class="not-italic font-medium text-gray-900 dark:text-gray-100">@lang('Expires')</dfn>
                                                            <span class="cursor-default"
                                                                title="{{ $qualification->expires_on->toFormattedDayDateString() }}">
                                                                @if ($qualification->expires_on->isToday())
                                                                    @lang('today')
                                                                @else
                                                                    {{ $qualification->expires_on->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}
                                                                @endif
                                                            </span>
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <p class="px-3 py-2">@lang('This user has no qualifications.')</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endcan
                    @endif

                    @can('contact', $attendee->attendance)
                        @if ($attendee->phone)
                            <div x-data="{
                                open: false,
                                gdprContact: $persist(false).using(sessionStorage).as('gdpr-contact-{{ $attendee->id }}')
                            }">
                                <h3 class="text-lg my-2 flex items-center space-x-1">
                                    <button @click="open = !open" x-bind:aria-pressed="open"
                                        class="flex items-center space-x-1">
                                        <x-icon.cheveron-down aria-hidden="true"
                                            class="w-4 h-4 fill-current transition-transform" ::class="open ? '' : '-rotate-90'" />
                                        <span>@lang('Contact Details')</span>
                                    </button>
                                </h3>
                                <div class="mb-3 space-y-4" x-cloak x-show="open" x-transition>
                                    <div class="space-y-2" :class="gdprContact && 'text-gray-600 dark:text-gray-400'">
                                        <p><strong>@lang('Notice'):</strong>
                                            @lang('You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.')
                                        </p>
                                        <p>
                                            <button class="flex items-start pl-1 gap-2" @click="gdprContact = !gdprContact">
                                                <x-icon.empty-outline class="mt-1 w-4 h-4 fill-current"
                                                    x-show="!gdprContact" />
                                                <x-icon.checkmark-outline class="mt-1 w-4 h-4 fill-current" x-cloak
                                                    x-show="gdprContact" />
                                                <span class="text-left">@lang('I have a legitimate reason to view these contact details')</span>
                                            </button>
                                        </p>
                                    </div>
                                    <div x-cloak x-show="gdprContact" class="space-y-2">
                                        <p>@lang('Phone'): <a
                                                class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                                href="tel:{{ $attendee->phone?->formatForMobileDialingInCountry('GB') }}">{{ $attendee->phone?->formatForCountry('GB') }}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div x-data="{
                            open: false,
                            gdprContact: $persist(false).using(sessionStorage).as('gdpr-emergency-contact-{{ $attendee->id }}')
                        }">
                            <h3 class="text-lg my-2 flex items-center space-x-1">
                                <button @click="open = !open" x-bind:aria-pressed="open"
                                    class="flex items-center space-x-1">
                                    <x-icon.cheveron-down aria-hidden="true"
                                        class="w-4 h-4 fill-current transition-transform" ::class="open ? '' : '-rotate-90'" />
                                    <span>@lang('Emergency Contact')</span>
                                </button>
                            </h3>
                            <div class="mb-3 space-y-4" x-cloak x-show="open" x-transition>
                                @if (!$booking->isToday())
                                    <p>@lang('You may only access emergency contact details on the day of the booking. If you need these details now please contact the Team Leader or District Lead Volunteer who will look them up for you.')</p>
                                @elseif (empty($attendee->emergency_phone))
                                    <p>@lang('No emergency contact has been provided by this member. If you need these details please contact the Team Leader or District Lead Volunteer who will look them up from the Scouts records.')</p>
                                @else
                                    <div class="space-y-2" :class="gdprContact && 'text-gray-600 dark:text-gray-400'">
                                        <p><strong>@lang('Notice'):</strong>
                                            @lang('You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.')
                                        </p>
                                        <p>
                                            <button class="flex items-start pl-1 gap-2" @click="gdprContact = !gdprContact">
                                                <x-icon.empty-outline class="my-1 w-4 h-4 fill-current"
                                                    x-show="!gdprContact" />
                                                <x-icon.checkmark-outline class="my-1 w-4 h-4 fill-current" x-cloak
                                                    x-show="gdprContact" />
                                                <span class="text-left">@lang('I have a legitimate reason to view these contact details')</span>
                                            </button>
                                        </p>
                                    </div>
                                    <div x-cloak x-show="gdprContact" class="space-y-2">
                                        <p>@lang('Name'): {{ $attendee->emergency_name }}</p>
                                        <p>@lang('Phone'): <a
                                                class="underline text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                                                href="tel:{{ $attendee->emergency_phone?->formatForMobileDialingInCountry('GB') }}">{{ $attendee->emergency_phone?->formatForCountry('GB') }}</a>
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endcan
                </div>

                <footer class="flex flex-wrap items-start gap-4 mt-4">
                    @include('booking.attendee.edit-button')

                    @can('update', $attendee->attendance)
                        {{-- don't show delete button if we can update attendance --}}
                    @elsecan('delete', $attendee->attendance)
                        @include('booking.attendee.delete-button')
                    @endcan

                    <x-button.secondary :href="route('booking.show', $booking)">
                        @lang('Back')
                    </x-button.secondary>
                </footer>
            </div>
        </div>
    </section>
</x-layout.app>
