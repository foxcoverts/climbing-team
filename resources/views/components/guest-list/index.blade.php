@use('App\Models\Attendance')
@use('Illuminate\Contracts\Auth\Access\Gate')
<aside class="my-2 flex-grow flex-shrink basis-80 max-w-xl">
    <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200">
        @lang('Guest list')
    </h2>

    @if ($attendee = $booking->attendees->find($booking->lead_instructor_id))
        @php($attendee->attendance->booking = $booking)
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
                        <x-guest-list.item :$booking :$attendee :$currentUser />
                    </div>
                </li>
            </ul>
        </div>
    @endif

    @foreach ($attendees as $status => $list)
        <div x-data="{ open: {{ $status == 'accepted' ? 'true' : 'false' }} }">
            <h3 class="text-lg my-2 flex items-center space-x-1">
                <button @click="open = !open" x-bind:aria-pressed="open" class="flex items-center space-x-1">
                    <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                        ::class="open ? '' : '-rotate-90'" />
                    <span>@lang("app.attendee.status.$status")</span>
                    <span x-show="!open" x-transition
                        class="bg-gray-200 dark:bg-gray-600 dark:text-white px-2 rounded-xl">{{ count($list) }}</span>
                </button>
                <hr class="grow" role="presentation" />
            </h3>
            <ul class="mb-3 space-y-1" x-show="open" x-transition>
                @foreach ($list as $attendee)
                    @php($attendee->attendance->booking = $booking)
                    <li class='list-disc ml-5'>
                        <div class="flex gap-1 items-center">
                            <x-guest-list.item :$booking :$attendee :$currentUser />
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach

    @empty($attendee)
        <p class="my-1">@lang('No one has responded to this booking yet.')</p>
    @endempty

    @if ($showTools)
        <footer class="flex flex-wrap items-start gap-4 mt-4">
            <div class="flex gap-4">{{-- flex-group --}}
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
            </div>
            <div class="flex flex-wrap gap-4">{{-- flex-group --}}
                @include('booking.partials.respond-button', [
                    'booking' => $booking,
                    'attendance' => $attendance(),
                ])
                @if ($booking->isFuture() && !$booking->isCancelled())
                    <x-button.secondary :href="route('booking.show.ics', $booking)" class="flex items-center gap-2">
                        <x-icon.download class="h-4 w-4 fill-current" />
                        <span class="whitespace-nowrap">@lang('Add to Calendar')</span>
                    </x-button.secondary>
                @endif
            </div>
        </footer>
    @endif
</aside>
