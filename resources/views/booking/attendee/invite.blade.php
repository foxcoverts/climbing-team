@use('App\Enums\Accreditation')
<x-layout.app :title="__('Invite Attendees')">
    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8 grid md:max-lg:grid-cols-booking xl:grid-cols-booking gap-4">
            <div class="max-w-prose md:max-lg:order-2 xl:order-2">
                @if ($users->isNotEmpty())
                    <form method="post" action="{{ route('booking.attendee.invite.store', $booking) }}"
                        x-data="{ form: { user_ids: [] }, submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                        @csrf

                        <fieldset x-data="checkboxes({{ Js::from($users->pluck('id')) }})" x-modelable="values" x-model="form.user_ids" class="m-0 p-0">
                            <legend
                                class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full mb-1">
                                {{ __('Invite Attendees') }}</legend>

                            <label class="my-2 block">
                                <x-input-checkbox name="all" @change="selectAll" x-effect="indeterminate($el)"
                                    autofocus />
                                {{ __('Invite all') }}
                            </label>

                            @foreach ($users as $user)
                                <label class="my-2 block">
                                    <x-input-checkbox value="{{ $user->id }}" name="user_ids[]" x-model="values" />

                                    <div class="inline-flex items-center gap-1">
                                        {{ $user->name }}

                                        @if ($user->isGuest())
                                            <x-badge.role :role="$user->role" class="text-xs whitespace-nowrap" />
                                        @endif

                                        @if ($user->isPermitHolder())
                                            <x-badge.permit-holder class="text-xs whitespace-nowrap" />
                                        @endif

                                        @if ($user->isUnder18())
                                            <x-badge.under-18 class="text-xs whitespace-nowrap" />
                                        @endif

                                        @if ($user->isKeyHolder())
                                            <x-badge.key-holder label="" class="text-xs whitespace-nowrap" />
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                            <x-input-error class="mt-2" :messages="$errors->get('user_ids')" />
                            <p class="text-sm mt-2">
                                {{ __('Someone missing? Only users who have verified their email address will appear here.') }}
                                {{ __('If you know their availability you may be able to ') }}
                                <a class="hover:underline"
                                    href="{{ route('booking.attendee.create', $booking) }}">{{ __('add them directly') }}</a>.
                            </p>
                        </fieldset>

                        <footer class="flex flex-wrap items-start gap-4 pt-4">
                            <x-button.primary class="whitespace-nowrap" disabled
                                x-bind:disabled="submitted || form.user_ids.length == 0" :label="__('Send Invitations')"
                                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Send Invitations') }}'" />

                            <x-button.secondary :href="route('booking.show', $booking)" :label="__('Back')" />
                        </footer>
                    </form>
                @else
                    <h3 class="text-lg font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        {{ __('Invite Attendees') }}</h3>
                    <p class="mt-2">{{ __('All eligible users have already been invited to this booking.') }}</p>
                    <p class="text-sm mt-2">
                        {{ __('Someone missing? Only users who have verified their email address will appear here.') }}
                        {{ __('If you know their availability you may be able to ') }}
                        <a class="hover:underline"
                            href="{{ route('booking.attendee.create', $booking) }}">{{ __('add them directly') }}</a>.
                    </p>
                    <footer class="flex flex-wrap items-start gap-4 pt-4">
                        <x-button.secondary :href="route('booking.show', $booking)" :label="__('Back')" />
                    </footer>
                @endif
            </div>

            <aside class="hidden sm:block">
                @include('booking.partials.details')
            </aside>
        </div>
    </section>
</x-layout.app>
