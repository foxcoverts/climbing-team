@use('App\Enums\BookingAttendeeStatus')
<x-layout.app :title="__('Add Attendee')">
    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8 grid md:max-lg:grid-cols-booking xl:grid-cols-booking gap-4">
            <div class="max-w-prose md:max-lg:order-2 xl:order-2">
                @if ($users->isNotEmpty())
                    <form method="post" action="{{ route('booking.attendee.store', $booking) }}" x-data="{ form: {}, submitted: false, }"
                        x-on:submit="setTimeout(() => submitted = true, 0)">
                        @csrf
                        <h3 class="text-xl font-medium border-b border-gray-800 dark:border-gray-200 w-full">
                            {{ __('Attendance') }}</h3>

                        <div class="space-y-1 my-1">
                            <div>
                                <x-input-label for="user_id" :value="__('Attendee')" />
                                <x-select-input id="user_id" name="user_id" class="mt-1 block" required autofocus
                                    :value="old('user_id')" x-model.fill="form.user_id">
                                    <option value="" disabled selected>{{ __('-- Select User --') }}</option>
                                    <x-select-input.collection :options="$users" label_key="name" />
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Availability')" />
                                <x-select-input id="status" name="status" class="mt-1 block" required
                                    :value="old('status', BookingAttendeeStatus::Accepted)" x-model.fill="form.status">
                                    <x-select-input.enum :options="BookingAttendeeStatus::class" :except="BookingAttendeeStatus::NeedsAction"
                                        lang="app.attendee.status.:value" />
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                <p class="text-sm pt-2">
                                    {{ __("If you do not know someone's availability you should ") }}
                                    <a class="hover:underline"
                                        href="{{ route('booking.attendee.invite', $booking) }}">{{ __('invite them') }}</a>
                                    {{ __(' instead.') }}
                                </p>
                            </div>
                        </div>

                        <footer class="flex flex-wrap items-start gap-4 mt-4">
                            <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted || !form.user_id"
                                :label="__('Add Attendee')"
                                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Add Attendee') }}'" />

                            <x-button.secondary :href="route('booking.show', $booking)" :label="__('Back')" />
                        </footer>
                    </form>
                @else
                    <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        {{ __('Attendance') }}</h3>
                    <p class="my-1">
                        {{ __('All users have already been invited to this booking. You may change their response on the guest list.') }}
                    </p>
                    <footer class="flex flex-wrap items-start gap-4 pt-2">
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
