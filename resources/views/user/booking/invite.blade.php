<x-layout.app :title="__('Invite to Bookings - :name', ['name' => $user->name])">
    <section class="p-4 sm:px-8">
        <header>
            <h1 class="text-2xl font-medium">{{ $user->name }}</h1>
        </header>

        @if ($bookings->isNotEmpty())
            <form method="post" action="{{ route('user.booking.invite.store', $user) }}"
                class="my-2 flex-grow flex-shrink basis-80 max-w-xl" x-data="{ form: { booking_ids: [] }, emailVerified: {{ Js::from($user->hasVerifiedEmail()) }}, submitted: false }"
                x-on:submit="setTimeout(() => submitted = true, 0)">
                @csrf

                <div class="space-y-6">
                    @unless ($user->hasVerifiedEmail())
                        <fieldset class="m-0 p-0">
                            <legend class="text-lg font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                                @lang('Email unverified')</legend>

                            <p class="mt-1 text-md">
                                @lang('This user has not verified their email address yet, invitations will not be delivered to them by email. Any bookings you invite this user to will be waiting for them in their invites list when they verify their account.')
                            </p>

                            <label class="mt-1 w-full flex gap-1 items-center">
                                <input type="checkbox" name="force" x-model="emailVerified" autofocus />
                                @lang('Send invitations with no email')
                            </label>
                        </fieldset>
                    @endunless

                    <fieldset x-data="checkboxes({{ Js::from($bookings->pluck('id')) }})" x-modelable="values" x-model="form.booking_ids"
                        x-show="emailVerified" {{ $user->hasVerifiedEmail() ? '' : 'x-transition x-cloak' }}
                        class="m-0 p-0">
                        <legend class="text-lg font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                            @lang('Invite to Bookings')</legend>

                        <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
                            @lang('Select the bookings below that you wish to invite this user to.')
                        </p>

                        <label class="mt-1 w-full flex gap-1 items-center">
                            <input type="checkbox" name="all" @change="selectAll" x-effect="indeterminate($el)"
                                autofocus />
                            <span>@lang('Invite all')</span>
                        </label>

                        @foreach ($bookings as $booking)
                            <label class="mt-1 w-full flex gap-1 items-center">
                                <input type="checkbox" value="{{ $booking->id }}" name="booking_ids[]"
                                    x-model="values" />
                                <span>@lang(':activity - :date', ['activity' => $booking->activity, 'date' => localDate($booking->start_at)->toFormattedDayDateString()])</span>
                                <x-badge.booking-status :status="$booking->status" class="text-xs" />
                            </label>
                        @endforeach
                        <x-input-error class="mt-2" :messages="$errors->get('booking_ids')" />
                    </fieldset>

                    <footer class="flex flex-wrap items-start gap-4">
                        <x-button.primary disabled class="whitespace-nowrap"
                            x-bind:disabled="submitted || !emailVerified || form.booking_ids.length == 0"
                            x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Send Invitations') }}'" />

                        <x-button.secondary :href="route('user.show', $user)">
                            @lang('Back')
                        </x-button.secondary>
                    </footer>
                </div>
            </form>
        @else
            <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                <h2 class="text-lg font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                    @lang('Invite to Bookings')</h2>
                <p class="mt-2">@lang('There are no bookings available to invite this user to.')</p>
                <footer class="flex flex-wrap items-start gap-4 pt-4">
                    <x-button.secondary :href="route('user.show', $user)">
                        @lang('Back')
                    </x-button.secondary>
                </footer>
            </div>
        @endif
    </section>
</x-layout.app>
