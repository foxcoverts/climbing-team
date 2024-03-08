@use('App\Enums\Accreditation')
<x-layout.app :title="__('Invite Attendees')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            @include('booking.partials.details')

            @if ($users->isNotEmpty())
                <form method="post" action="{{ route('booking.attendee.invite.store', $booking) }}"
                    class="my-2 flex-grow flex-shrink basis-80 max-w-xl" x-data="{ form: { user_ids: [] } }">
                    @csrf

                    <fieldset x-data="checkboxes({{ $users->pluck('id') }})" x-modelable="values" x-model="form.user_ids" class="m-0 p-0">
                        <legend class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
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

                                <x-badge.role :role="$user->role" class="text-xs" />

                                @if ($user->isPermitHolder())
                                    <x-badge.accreditation :accreditation="Accreditation::PermitHolder" class="text-xs" />
                                @endif
                            </label>
                        @endforeach
                        <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        <p class="text-sm pt-2">
                            @lang('Someone missing? Only users who have verified their email address will appear here.')
                            @lang('If you know their availability you may be able to ')
                            <a class="hover:underline"
                                href="{{ route('booking.attendee.create', $booking) }}">@lang('add them directly')</a>.
                        </p>
                    </fieldset>

                    <footer class="flex items-start gap-4 pt-4">
                        <x-button.primary disabled x-bind:disabled="form.user_ids.length == 0">
                            @lang('Invite')
                        </x-button.primary>

                        <x-button.secondary :href="route('booking.show', $booking)">
                            @lang('Back')
                        </x-button.secondary>
                    </footer>
                </form>
            @else
                <div class="my-2 flex-grow max-w-xl">
                    <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        @lang('Invite Attendees')</h3>
                    <p>@lang('All eligible users have already been invited to this booking.')</p>
                    <footer class="flex items-start gap-4 pt-4">
                        <x-button.secondary :href="route('booking.show', $booking)">
                            @lang('Back')
                        </x-button.secondary>
                    </footer>
                </div>
            @endif
        </div>
    </section>
</x-layout.app>
