<x-layout.app :title="__('Invite to Bookings - :name', ['name' => $user->name])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ $user->name }}
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8">
            @if ($bookings->isNotEmpty())
                <form method="post" action="{{ route('user.booking.invite.store', $user) }}" x-data="{
                    form: { booking_ids: [] },
                    emailVerified: {{ Js::from($user->hasVerifiedEmail()) }},
                    submitted: false
                }"
                    x-on:submit="setTimeout(() => submitted = true, 0)">
                    @csrf

                    <div class="space-y-6 max-w-prose">
                        @unless ($user->hasVerifiedEmail())
                            <fieldset class="m-0 p-0">
                                <legend class="text-xl font-medium border-b border-gray-800 dark:border-gray-200 w-full">
                                    {{ __('Email unverified') }}</legend>

                                <p class="mt-1 text-md">
                                    {{ __('This user has not verified their email address yet, invitations will not be delivered to them by email. Any bookings you invite this user to will be waiting for them in their invites list when they verify their account.') }}
                                </p>

                                <label class="mt-1 block">
                                    <x-input-checkbox name="force" x-model="emailVerified" autofocus />
                                    {{ __('Send invitations with no email') }}
                                </label>
                            </fieldset>
                        @endunless

                        <fieldset x-data="checkboxes({{ Js::from($bookings->pluck('id')) }})" x-modelable="values" x-model="form.booking_ids"
                            x-show="emailVerified" {{ $user->hasVerifiedEmail() ? '' : 'x-transition x-cloak' }}
                            class="m-0 p-0">
                            <legend class="text-xl font-medium border-b border-gray-800 dark:border-gray-200 w-full">
                                {{ __('Invite to Bookings') }}</legend>

                            <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
                                {{ __('Select the bookings below that you wish to invite this user to.') }}
                            </p>

                            <label class="my-2 block">
                                <x-input-checkbox name="all" @change="selectAll" x-effect="indeterminate($el)"
                                    autofocus />
                                {{ __('Invite to all') }}
                            </label>

                            @foreach ($bookings as $booking)
                                <label class="my-2 block">
                                    <x-input-checkbox value="{{ $booking->id }}" name="booking_ids[]"
                                        x-model="values" />
                                    {{ $booking->activity }} -
                                    <span x-data="{{ Js::from(['start_at' => localDate($booking->start_at, $booking->timezone)]) }}"
                                        x-text="dateString(start_at)">{{ localDate($booking->start_at, $booking->timezone)->toFormattedDayDateString() }}</span>
                                    <x-badge.booking-status :status="$booking->status" class="text-xs" />
                                </label>
                            @endforeach
                            <x-input-error class="mt-2" :messages="$errors->get('booking_ids')" />
                        </fieldset>

                        <footer class="flex flex-wrap items-start gap-4">
                            <x-button.primary disabled class="whitespace-nowrap" :label="__('Send Invitations')"
                                x-bind:disabled="submitted || !emailVerified || form.booking_ids.length == 0"
                                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Send Invitations') }}'" />

                            <x-button.secondary :href="route('user.show', $user)" :label="__('Back')" />
                        </footer>
                    </div>
                </form>
            @else
                <div class="max-w-prose">
                    <h2 class="text-xl font-medium border-b border-gray-800 dark:border-gray-200 w-full">
                        {{ __('Invite to Bookings') }}</h2>
                    <p class="mt-2">{{ __('There are no bookings available to invite this user to.') }}</p>
                    <footer class="flex flex-wrap items-start gap-4 pt-4">
                        <x-button.secondary :href="route('user.show', $user)" :label="__('Back')" />
                    </footer>
                </div>
            @endif
        </div>
    </section>
</x-layout.app>
