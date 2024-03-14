@use('App\Enums\AttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__('Update Attendance')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            @include('booking.partials.details')

            <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl" x-data="{ form: {}, submitted: false }">
                <div class="space-y-1">
                    <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        @lang('Attendance')</h3>

                    <div>
                        <x-fake-label :value="__('Attendee')" />
                        <x-fake-input :value="$attendee->name" class="mt-1" />
                    </div>

                    @if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('update', $attendee->attendance))
                        <form method="post" action="{{ route('booking.attendee.update', [$booking, $attendee]) }}"
                            id="update-attendance" x-on:submit="setTimeout(() => submitted = true, 0)">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="status" :value="__('Availability')"
                                    class="not-italic font-bold after:content-[':']" />
                                <x-select-input id="status" name="status" class="mt-1 block" required
                                    :value="old('status', $attendee->attendance->status)" x-model.fill="form.status">
                                    @if ($attendee->attendance->status == AttendeeStatus::NeedsAction)
                                        <option value="" selected disabled>
                                            @lang('app.attendee.status.' . AttendeeStatus::NeedsAction->value)
                                        </option>
                                    @endif
                                    <x-select-input.enum :options="AttendeeStatus::class" lang="app.attendee.status.:value"
                                        :except="AttendeeStatus::NeedsAction" />
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>

                            @if ($attendee->attendance->comment)
                                <div>
                                    <x-input-label for="comment" :value="__('Comment')" />
                                    <x-text-input id="comment" name="comment" class="w-full mt-1" :value="old('comment', $attendee->attendance->comment)"
                                        x-model.fill="form.comment" />
                                    <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                                </div>
                            @endif
                        </form>
                    @else
                        @if ($attendee->is($booking->lead_instructor))
                            <div>
                                <x-fake-label :value="__('Status')" />
                                <x-fake-input class="mt-1" :value="__('Lead Instructor')" />
                            </div>
                        @else
                            <div>
                                <x-fake-label :value="__('Status')" />
                                <x-fake-input class="mt-1" :value='__("app.attendee.status.{$attendee->attendance->status->value}")' />
                            </div>
                        @endif

                        @if ($attendee->attendance->comment)
                            <div>
                                <x-fake-label :value="__('Comment')" />
                                <x-fake-input :value="$attendee->attendance->comment" />
                            </div>
                        @endif
                    @endif
                </div>

                <footer class="flex items-start gap-4 mt-4">
                    @if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('update', $attendee->attendance))
                        <x-button.primary form="update-attendance" x-bind:disabled="submitted || form.status == ''"
                            x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Update') }}'" />
                    @endif
                    @can('delete', $attendee->attendance)
                        <form method="post" action="{{ route('booking.attendee.destroy', [$booking, $attendee]) }}"
                            x-data="{ submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                            @csrf
                            @method('delete')
                            <x-button.danger x-bind:disabled="submitted"
                                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Remove') }}'" />
                        </form>
                    @endcan
                    <x-button.secondary :href="route('booking.show', $booking)">
                        @lang('Back')
                    </x-button.secondary>
                </footer>
            </div>
        </div>
    </section>
</x-layout.app>
