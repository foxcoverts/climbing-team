@use('App\Enums\Accreditation')
<x-layout.app :title="__('Invite Attendees')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header', ['booking' => $booking])
        @include('booking.partials.details', ['booking' => $booking])

        @if ($users->isNotEmpty())
            <form method="post" action="{{ route('booking.attendee.invite.store', $booking) }}" class="space-y-1"
                x-data="{ form: { user_ids: [] } }">
                @csrf

                <fieldset x-data="checkboxes({{ $users->pluck('id') }})" x-modelable="values" x-model="form.user_ids">
                    <legend class="text-xl font-medium">{{ __('Invite Attendees') }}</legend>

                    <label class="mt-1  w-full flex space-x-1 items-center">
                        <input type="checkbox" x-model="all" x-effect="$el.indeterminate = indeterminate()" />
                        <span>{{ __('Invite all') }}</span>
                    </label>

                    @foreach ($users as $user)
                        <label class="mt-1 w-full flex space-x-1 items-center">
                            <input type="checkbox" value="{{ $user->id }}" name="user_ids[]" x-model="values" />
                            <span>{{ $user->name }}</span>

                            <x-badge.role :role="$user->role" class="text-xs" />

                            @if ($user->isPermitHolder())
                                <x-badge.accreditation :accreditation="Accreditation::PermitHolder" class="text-xs" />
                            @endif
                        </label>
                    @endforeach
                    <p class="text-sm pt-2">
                        {{ __('Someone missing? Only users who have verified their email address will appear here.') }}
                    </p>
                    <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                </fieldset>

                <footer class="flex items-center gap-4 pt-4">
                    <x-button.primary disabled x-bind:disabled="form.user_ids.length == 0">
                        {{ __('Invite') }}
                    </x-button.primary>

                    <x-button.secondary :href="route('booking.show', $booking)">
                        {{ __('Back') }}
                    </x-button.secondary>
                </footer>
            </form>
        @else
            <h3 class="text-xl font-medium">{{ __('Invite Attendees') }}</h3>
            <p>{{ __('All users have already been invited to this booking.') }}</p>
            <footer class="flex items-center gap-4 pt-2">
                <x-button.secondary :href="route('booking.show', $booking)">
                    {{ __('Back') }}
                </x-button.secondary>
            </footer>
        @endif
    </section>
</x-layout.app>
