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
                x-data="{
                    form: { user_ids: [] },
                    all: false,
                    all_user_ids: {{ $users->pluck('id') }},
                    toggleAll() {
                        if (this.all) {
                            this.form.user_ids = this.all_user_ids;
                        } else {
                            this.form.user_ids = [];
                        }
                    },
                    checkAll() {
                        if (this.all_user_ids.every(value => this.form.user_ids.includes(value))) {
                            this.all = true;
                            $refs.all.indeterminate = false;
                        } else {
                            this.all = false;
                            $refs.all.indeterminate = (this.form.user_ids.length > 0);
                        }
                    },
                }">
                @csrf

                <fieldset>
                    <legend class="text-xl font-medium">{{ __('Invite Attendees') }}</legend>

                    <label class="mt-1 block w-full">
                        <input type="checkbox" x-ref="all" x-model="all" @change="toggleAll" />
                        {{ __('Invite all') }}</label>

                    @foreach ($users as $user)
                        <label class="mt-1 block w-full">
                            <input type="checkbox" value="{{ $user->id }}" name="user_ids[]"
                                x-model="form.user_ids" @change="checkAll" />
                            {{ $user->name }}</label>
                    @endforeach
                    <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                </fieldset>

                <footer class="flex items-center gap-4 pt-2">
                    <x-button.primary x-bind:disabled="form.user_ids.length == 0">
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
