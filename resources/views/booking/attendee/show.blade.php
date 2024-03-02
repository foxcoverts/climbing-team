@use('App\Enums\AttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__('Update Attendance')">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header')

        <div class="md:flex md:space-x-4">
            @include('booking.partials.details')

            <div class="flex-grow my-2">
                <div class="space-y-1">
                    <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        {{ __('Attendance') }}</h3>

                    <div>
                        <x-input-label for="user_id" :value="__('Attendee')" />
                        <x-text-input id="user_id" class="mt-1 block" readonly :value="$attendee->name" />
                    </div>

                    @if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('update', $attendee->attendance))
                        <form method="post" action="{{ route('booking.attendee.update', [$booking, $attendee]) }}"
                            id="update-attendance">
                            @csrf
                            @method('PUT')

                            <div>
                                <x-input-label for="status" :value="__('Status')"
                                    class="not-italic font-bold after:content-[':']" />
                                <x-select-input id="status" name="status" class="mt-1 block" required
                                    :value="old('status', $attendee->attendance->status)">
                                    <x-select-input.enum :options="AttendeeStatus::class" lang="app.attendee.status.:value"
                                        :except="[AttendeeStatus::NeedsAction]" />
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                            </div>
                        </form>
                    @else
                        <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <x-text-input id="status" class="mt-1 block" readonly
                                value="{{ __("app.attendee.status.{$attendee->attendance->status->value}") }}" />
                        </div>
                    @endif
                </div>

                <footer class="flex items-center gap-4 mt-4">
                    @if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('update', $attendee->attendance))
                        <x-button.primary form="update-attendance">
                            {{ __('Update') }}
                        </x-button.primary>
                    @endif
                    @can('delete', $attendee->attendance)
                        <form method="post" action="{{ route('booking.attendee.destroy', [$booking, $attendee]) }}">
                            @csrf
                            @method('delete')
                            <x-button.danger>
                                @if ($attendee->attendance->needsAction())
                                    {{ __('Remove') }}
                                @else
                                    {{ __('Remove') }}
                                @endif
                            </x-button.danger>
                        </form>
                    @endcan
                    <x-button.secondary :href="route('booking.show', $booking)">
                        {{ __('Back') }}
                    </x-button.secondary>
                </footer>
            </div>
        </div>
    </section>
</x-layout.app>
