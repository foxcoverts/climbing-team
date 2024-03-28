@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__(':name - Attendance', ['name' => $attendee->name])">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            @include('booking.partials.details')

            <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                <div class="space-y-1">
                    <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        {{ $attendee->name }}</h2>

                    <div class="flex flex-wrap items-center gap-1">
                        @if ($attendee->is($booking->lead_instructor))
                            <x-badge.lead-instructor class="text-sm" />
                        @else
                            <x-badge.attendee-status :status="$attendee->attendance->status" class="text-sm" />
                        @endif

                        @if ($attendee->isPermitHolder())
                            <x-badge.accreditation :accreditation="Accreditation::PermitHolder" class="text-sm" />
                        @endif

                        @if ($attendee->isGuest())
                            <x-badge.role :role="Role::Guest" class="text-sm" />
                        @endif

                        @if ($attendee->isUnder18())
                            <x-badge.under-18 class="text-sm" />
                        @endif
                    </div>

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
                                    <hr class="grow" role="presentation" />
                                </h3>
                                <div class="mb-3 space-y-4" x-show="open" x-transition>
                                    <div class="space-y-2" :class="gdprContact && 'text-gray-600 dark:text-gray-400'">
                                        <p><strong>@lang('Notice'):</strong>
                                            @lang('You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.')
                                        </p>
                                        <p>
                                            <button class="flex items-center pl-1 gap-1"
                                                @click="gdprContact = !gdprContact">
                                                <x-icon.empty-outline class="w-4 h-4 fill-current" x-show="!gdprContact" />
                                                <x-icon.checkmark-outline class="w-4 h-4 fill-current" x-cloak
                                                    x-show="gdprContact" />
                                                @lang('I have a legitimate reason to view these contact details')
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
                                <hr class="grow" role="presentation" />
                            </h3>
                            <div class="mb-3 space-y-4" x-show="open" x-transition>
                                @empty($attendee->emergency_phone)
                                    <p>@lang('No emergency contact has been provided by this member. If you need these details please contact the Team Leader or District Lead Volunteer who will look them up from the Scouts records.')</p>
                                @else
                                    <div class="space-y-2" :class="gdprContact && 'text-gray-600 dark:text-gray-400'">
                                        <p><strong>@lang('Notice'):</strong>
                                            @lang('You may only use these details to contact team members regarding legitimate Climbing Team matters. Any other use of these contact details, no matter how well intended, will be in breach of UK data protection laws.')
                                        </p>
                                        <p>
                                            <button class="flex items-center pl-1 gap-1" @click="gdprContact = !gdprContact">
                                                <x-icon.empty-outline class="w-4 h-4 fill-current" x-show="!gdprContact" />
                                                <x-icon.checkmark-outline class="w-4 h-4 fill-current" x-cloak
                                                    x-show="gdprContact" />
                                                @lang('I have a legitimate reason to view these contact details')
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
                                @endempty
                            </div>
                        </div>
                    @endcan
                </div>

                <footer class="flex items-start gap-4 mt-4">
                    @can('update', $attendee->attendance)
                        <x-button.primary :href="route('booking.attendee.edit', [$booking, $attendee])">
                            @lang('Edit')
                        </x-button.primary>
                    @elsecan('delete', $attendee->attendance)
                        <form method="post" action="{{ route('booking.attendee.destroy', [$booking, $attendee]) }}"
                            x-data="{ submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                            @csrf
                            @method('delete')
                            <x-button.danger x-bind:disabled="submitted"
                                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Remove') }}'" />
                        </form>
                    @endcan
                    <x-button.secondary :href="route('booking.show', $booking)">
                        @lang('Back')
                    </x-button.secondary>
                </footer>
            </div>
        </div>
    </section>
</x-layout.app>
