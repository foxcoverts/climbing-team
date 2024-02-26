@use('App\Enums\Accreditation')
@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Invite Attendees')">
    <section class="p-4 sm:p-8 max-w-xl">
        @include('booking.partials.header', ['booking' => $booking])
        <div class="space-y-1 mt-2 max-w-xl flex-grow">
            <p class="text-lg text-gray-800 dark:text-gray-200 border-b border-gray-800 dark:border-gray-200">
                {{ $booking->location }}</p>

            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('When') }}</dfn>
                @if ($booking->start_at->isSameDay($booking->end_at))
                    {{ __(':start_date from :start_time to :end_time', [
                        'start_date' => $booking->start_at->toFormattedDayDateString(),
                        'start_time' => $booking->start_at->format('H:i'),
                        'end_time' => $booking->end_at->format('H:i'),
                    ]) }}
                @else
                    {{ __(':start to :end', [
                        'start' => $booking->start_at->toDayDateTimeString(),
                        'end' => $booking->end_at->toDayDateTimeString(),
                    ]) }}
                @endif
            </p>
            <p><dfn class="not-italic font-bold after:content-[':']">{{ __('Duration') }}</dfn>
                {{ $booking->start_at->diffAsCarbonInterval($booking->end_at) }}</p>
        </div>

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

                            <x-badge.role :role="$user->role" />

                            @if ($user->accreditations->contains(Accreditation::PermitHolder))
                                <x-badge.accreditation :accreditation="Accreditation::PermitHolder" />
                            @endif
                        </label>
                    @endforeach
                    <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                </fieldset>

                <footer class="flex items-center gap-4 pt-2">
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
