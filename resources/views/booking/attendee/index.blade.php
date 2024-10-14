@use('App\Enums\BookingAttendeeStatus')
<x-layout.app :title="__('Roll call')">
    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8 grid md:max-lg:grid-cols-booking xl:grid-cols-booking gap-4">
            <div class="max-w-prose md:max-lg:order-2 xl:order-2">
                <form method="POST" action="{{ route('booking.attendee.updateMany', $booking) }}">
                    @csrf
                    @method('PATCH')

                    <h2
                        class="text-xl font-medium text-gray-900 dark:text-gray-100 border-b border-gray-800 dark:border-gray-200">
                        {{ __('Roll call') }}</h2>

                    <div class="space-y-2 mt-2">
                        @if ($lead_instructor)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Lead Instructor') }}
                                </h3>
                                <ul>
                                    <li>
                                        <label class="block my-2">
                                            <x-input-checkbox name="attendee_ids[]" checked disabled />
                                            {{ $lead_instructor->name }}
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        @if (isset($attendees[BookingAttendeeStatus::Accepted->value]))
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('app.attendee.status.' . BookingAttendeeStatus::Accepted->value) }}
                                </h3>
                                <ul>
                                    @foreach ($attendees[BookingAttendeeStatus::Accepted->value] as $attendee)
                                        <li>
                                            <label class="block my-2 text-gray-800 dark:text-gray-200">
                                                <x-input-checkbox name="attendee_ids[]" value="{{ $attendee->id }}"
                                                    checked disabled />
                                                {{ $attendee->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <p class="text-gray-800 dark:text-gray-200">
                            {{ __('Please select any additional people below who have come to the booking. Their attendance will be recorded and you will then be able to view their qualifications and contact details.') }}
                        </p>

                        @foreach ($attendees as $status => $list)
                            @unless ($status == BookingAttendeeStatus::Accepted->value)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        {{ __("app.attendee.status.$status") }}
                                    </h3>
                                    <ul>
                                        @foreach ($list as $attendee)
                                            <li>
                                                <label class="block my-2">
                                                    <x-input-checkbox name="attendee_ids[]" value="{{ $attendee->id }}" />
                                                    {{ $attendee->name }}
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endunless
                        @endforeach

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                {{ __('Other') }}
                            </h3>
                            <ul>
                                @foreach ($nonAttendees as $user)
                                    <li>
                                        <label class="block my-2">
                                            <x-input-checkbox name="attendee_ids[]" value="{{ $user->id }}" />
                                            {{ $user->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <footer class="flex flex-wrap items-center gap-4 mt-6">
                        <x-button.primary :label="__('Mark Present')" />
                        <x-button.secondary :href="route('booking.show', $booking)" :label="__('Back')" />
                    </footer>
                </form>
            </div>

            <aside class="hidden sm:block">
                @include('booking.partials.details')
            </aside>
        </div>
    </section>
</x-layout.app>
