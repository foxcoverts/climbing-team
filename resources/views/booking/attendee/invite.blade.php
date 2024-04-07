@use('App\Enums\Accreditation')
<x-layout.app :title="__('Invite Attendees')">
    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8 flex flex-wrap-reverse gap-4">
            @include('booking.partials.details')

            @if ($users->isNotEmpty())
                <form method="post" action="{{ route('booking.attendee.invite.store', $booking) }}"
                    class="flex-grow flex-shrink basis-80 max-w-prose" x-data="{ form: { user_ids: [] }, submitted: false }"
                    x-on:submit="setTimeout(() => submitted = true, 0)">
                    @csrf

                    <fieldset x-data="checkboxes({{ Js::from($users->pluck('id')) }})" x-modelable="values" x-model="form.user_ids" class="m-0 p-0">
                        <legend class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full mb-1">
                            @lang('Invite Attendees')</legend>

                        <label class="mt-1 w-full flex gap-1 items-center">
                            <input type="checkbox" name="all" @change="selectAll" x-effect="indeterminate($el)"
                                autofocus />
                            <span>@lang('Invite all')</span>
                        </label>

                        @foreach ($users as $user)
                            <label class="mt-1 w-full flex gap-1 items-center">
                                <input type="checkbox" value="{{ $user->id }}" name="user_ids[]" x-model="values" />
                                <span>{{ $user->name }}</span>

                                @if ($user->isGuest())
                                    <x-badge.role :role="$user->role" class="text-xs" />
                                @endif

                                @if ($user->isPermitHolder())
                                    <x-badge.permit-holder class="text-xs" />
                                @endif
                            </label>
                        @endforeach
                        <x-input-error class="mt-2" :messages="$errors->get('user_ids')" />
                        <p class="text-sm mt-2">
                            @lang('Someone missing? Only users who have verified their email address will appear here.')
                            @lang('If you know their availability you may be able to ')
                            <a class="hover:underline"
                                href="{{ route('booking.attendee.create', $booking) }}">@lang('add them directly')</a>.
                        </p>
                    </fieldset>

                    <footer class="flex flex-wrap items-start gap-4 pt-4">
                        <x-button.primary class="whitespace-nowrap" disabled
                            x-bind:disabled="submitted || form.user_ids.length == 0"
                            x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Send Invitations') }}'" />

                        <x-button.secondary :href="route('booking.show', $booking)">
                            @lang('Back')
                        </x-button.secondary>
                    </footer>
                </form>
            @else
                <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                    <h3 class="text-lg font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        @lang('Invite Attendees')</h3>
                    <p class="mt-2">@lang('All eligible users have already been invited to this booking.')</p>
                    <p class="text-sm mt-2">
                        @lang('Someone missing? Only users who have verified their email address will appear here.')
                        @lang('If you know their availability you may be able to ')
                        <a class="hover:underline"
                            href="{{ route('booking.attendee.create', $booking) }}">@lang('add them directly')</a>.
                    </p>
                    <footer class="flex flex-wrap items-start gap-4 pt-4">
                        <x-button.secondary :href="route('booking.show', $booking)">
                            @lang('Back')
                        </x-button.secondary>
                    </footer>
                </div>
            @endif
        </div>
    </section>
</x-layout.app>
