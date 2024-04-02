@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Add Attendee')">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap-reverse gap-4">
            @include('booking.partials.details')

            <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                @if ($users->isNotEmpty())
                    <form method="post" action="{{ route('booking.attendee.store', $booking) }}" x-data="{ form: {}, submitted: false, }"
                        x-on:submit="setTimeout(() => submitted = true, 0)">
                        @csrf
                        <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                            @lang('Attendance')</h3>

                        <div class="space-y-1 my-1">
                            <div>
                                <x-input-label for="user_id" :value="__('Attendee')" />
                                <x-select-input id="user_id" name="user_id" class="mt-1 block" required autofocus
                                    :value="old('user_id')" x-model.fill="form.user_id">
                                    <option value="" disabled selected>@lang('-- Select User --')</option>
                                    <x-select-input.collection :options="$users" label_key="name" />
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Availability')" />
                                <x-select-input id="status" name="status" class="mt-1 block" required
                                    :value="old('status', AttendeeStatus::Accepted)" x-model.fill="form.status">
                                    <x-select-input.enum :options="AttendeeStatus::class" :except="AttendeeStatus::NeedsAction"
                                        lang="app.attendee.status.:value" />
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                <p class="text-sm pt-2">
                                    @lang("If you do not know someone's availability you should ")
                                    <a class="hover:underline"
                                        href="{{ route('booking.attendee.invite', $booking) }}">@lang('invite them')</a>
                                    @lang(' instead.')
                                </p>
                            </div>
                        </div>

                        <footer class="flex flex-wrap items-start gap-4 mt-4">
                            <x-button.primary x-bind:disabled="submitted || !form.user_id" class="whitespace-nowrap"
                                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Add Attendee') }}'" />

                            <x-button.secondary :href="route('booking.show', $booking)">
                                @lang('Back')
                            </x-button.secondary>
                        </footer>
                    </form>
                @else
                    <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        @lang('Attendance')</h3>
                    <p class="my-1">
                        @lang('All users have already been invited to this booking. You may change their response on the guest list.')
                    </p>
                    <footer class="flex flex-wrap items-start gap-4 pt-2">
                        <x-button.secondary :href="route('booking.show', $booking)">
                            @lang('Back')
                        </x-button.secondary>
                    </footer>
                @endif
            </div>
        </div>
    </section>
</x-layout.app>
