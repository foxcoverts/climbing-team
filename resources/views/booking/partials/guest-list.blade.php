@use('App\Models\Attendance')
@use('Illuminate\Contracts\Auth\Access\Gate')
@props(['booking', 'attendees' => collect([]), 'attendance' => null, 'showTools' => true])
<aside class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
    <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200">
        @lang('Guest list')
    </h2>

    @if ($attendee = $booking->attendees->find($booking->lead_instructor))
        <div x-data="{ open: true }">
            <h3 class="text-lg my-2 flex items-center space-x-1">
                <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                    <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                        ::class="open ? '' : '-rotate-90'" />
                    <span>@lang('Lead Instructor')</span>
                </button>
                <hr class="grow" role="presentation" />
            </h3>
            <ul class="mb-3 space-y-1 list-disc ml-5" x-show="open" x-transition>
                <li>
                    <div class="flex gap-1 items-center">
                        @include('booking.partials.guest-list.item')
                    </div>
                </li>
            </ul>
        </div>
    @endif

    @foreach ($attendees->groupBy('attendance.status') as $status => $attendees)
        <div x-data="{ open: {{ $status == 'accepted' ? 'true' : 'false' }} }">
            <h3 class="text-lg my-2 flex items-center space-x-1">
                <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                    <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                        ::class="open ? '' : '-rotate-90'" />
                    <span>@lang("app.attendee.status.$status")</span>
                    <span x-show="!open" x-transition
                        class="bg-gray-200 dark:bg-gray-600 dark:text-white px-2 rounded-xl">{{ count($attendees) }}</span>
                </button>
                <hr class="grow" role="presentation" />
            </h3>
            <ul class="mb-3 space-y-1" x-show="open" x-transition>
                @foreach ($attendees as $attendee)
                    <li class='list-disc ml-5'>
                        <div class="flex gap-1 items-center">
                            @include('booking.partials.guest-list.item')
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

    @if (!$attendee)
        <p class="my-1">@lang('No one has responded to this booking yet.')</p>
    @endempty

    @if ($showTools)
        <footer class="flex items-start gap-4 mt-4">
            @if (
                $booking->isFuture() &&
                    !$booking->isCancelled() &&
                    app(Gate::class)->check('create', [Attendance::class, $booking]))
                <x-button.primary :href="route('booking.attendee.invite', $booking)">
                    @lang('Invite')
                </x-button.primary>
                <x-button.primary :href="route('booking.attendee.create', $booking)">
                    @lang('Add')
                </x-button.primary>
            @endif
            @include('booking.partials.respond-button')
        </footer>
    @endif
</aside>
