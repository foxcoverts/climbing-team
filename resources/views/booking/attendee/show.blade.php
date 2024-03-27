@use('App\Enums\AttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__('Update Attendance')">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            @include('booking.partials.details')

            <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                <div class="space-y-1">
                    <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        @lang('Attendance')</h3>
                    <div>
                        <x-fake-label :value="__('Attendee')" />
                        <x-fake-input :value="$attendee->name" class="mt-1" />
                    </div>

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
                </div>

                <footer class="flex items-start gap-4 mt-4">
                    @can('update', $attendee->attendance)
                        <x-button.primary :href="route('booking.attendee.edit', [$booking, $attendee])">
                            @lang('Edit')
                        </x-button.primary>
                    @endcan
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
