@use('App\Enums\AttendeeStatus')
<x-layout.app :title="__('Roll call')">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap-reverse gap-4">
            <div class="w-full max-w-xl">
                @include('booking.partials.details')
            </div>

            <aside class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                <form method="POST" action="">
                    @csrf
                    @method('PATCH')

                    <h2
                        class="text-xl font-medium text-gray-900 dark:text-gray-100 border-b border-gray-800 dark:border-gray-200">
                        @lang('Roll call')</h2>

                    <div class="space-y-2 mt-2">
                        @if ($lead_instructor)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    @lang('Lead Instructor')
                                </h3>
                                <ul>
                                    <li>
                                        <label class="flex items-center gap-1">
                                            <input type="checkbox" name="attendee_ids[]" checked disabled
                                                class="cursor-not-allowed" />
                                            <span>{{ $lead_instructor->name }}</span>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        @if ($list = $attendees[AttendeeStatus::Accepted->value])
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                    @lang('app.attendee.status.' . AttendeeStatus::Accepted->value)
                                </h3>
                                <ul>
                                    @foreach ($list as $attendee)
                                        <li>
                                            <label class="flex items-center gap-1 text-gray-800 dark:text-gray-200">
                                                <input type="checkbox" name="attendee_ids[]" value="{{ $attendee->id }}"
                                                    checked disabled class="cursor-not-allowed" />
                                                <span>{{ $attendee->name }}</span>
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <p class="text-gray-800 dark:text-gray-200">
                            @lang('Please select any additional people below who have come to the booking. Their attendance will be recorded and you will then be able to view their qualifications and contact details.')
                        </p>

                        @foreach ($attendees as $status => $list)
                            @unless ($status == AttendeeStatus::Accepted->value)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        @lang("app.attendee.status.$status")
                                    </h3>
                                    <ul>
                                        @foreach ($list as $attendee)
                                            <li>
                                                <label
                                                    class="flex items-center gap-1 text-gray-800 dark:text-gray-200 hover:text-black dark:hover:text-white">
                                                    <input type="checkbox" name="attendee_ids[]"
                                                        value="{{ $attendee->id }}" />
                                                    <span>{{ $attendee->name }}</span>
                                                </label>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endunless
                        @endforeach

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                @lang('Other')
                            </h3>
                            <ul>
                                @foreach ($nonAttendees as $user)
                                    <li>
                                        <label
                                            class="flex items-center gap-1 text-gray-800 dark:text-gray-200 hover:text-black dark:hover:text-white">
                                            <input type="checkbox" name="attendee_ids[]" value="{{ $user->id }}" />
                                            <span>{{ $user->name }}</span>
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <footer class="flex flex-wrap items-center gap-4 mt-6">
                        <x-button.primary>@lang('Update Attendance')</x-button.primary>
                        <x-button.secondary :href="route('booking.show', $booking)">@lang('Back')</x-button.secondary>
                    </footer>
                </form>
            </aside>
        </div>
    </section>
</x-layout.app>
