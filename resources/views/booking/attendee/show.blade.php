@use('App\Enums\AttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="__(':name - Attendance', ['name' => $attendee->name])">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            @include('booking.partials.details')

            <div class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
                <div class="space-y-1">
                    <h3 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200 w-full">
                        {{ $attendee->name }}</h3>

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
                    <x-button.secondary :href="route('booking.show', $booking)">
                        @lang('Back')
                    </x-button.secondary>
                </footer>
            </div>
        </div>
    </section>
</x-layout.app>
