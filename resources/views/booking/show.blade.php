@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('App\Models\Attendance')
@use('Illuminate\Contracts\Auth\Access\Gate')
<x-layout.app :title="$booking->activity . ' - ' . localDate($booking->start_at)->toFormattedDayDateString()">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header', ['booking' => $booking])

        <div class="md:flex md:space-x-4">
            @include('booking.partials.details', ['booking' => $booking])

            <aside class="my-2 flex-grow max-w-xl">
                <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200">
                    {{ __('Guest list') }}
                </h2>

                @forelse ($attendees->groupBy('attendance.status') as $status => $attendees)
                    <h3 class="text-lg">{{ __("app.attendee.status.$status") }}</h3>
                    <ul class="mb-3 space-y-1">
                        @foreach ($attendees as $attendee)
                            <li class="flex space-x-1 items-center">
                                @can('view', $attendee->attendance)
                                    <a
                                        href="{{ route('booking.attendee.show', [$booking, $attendee]) }}">{{ $attendee->name }}</a>
                                @else
                                    <span>{{ $attendee->name }}</span>
                                @endcan

                                @if ($attendee->accreditations->contains(Accreditation::PermitHolder))
                                    <x-badge.accreditation :accreditation="Accreditation::PermitHolder" class="text-xs" />
                                @endif

                                @if ($attendee->role == Role::Guest)
                                    <x-badge.role :role="Role::Guest" class="text-xs" />
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @empty
                    <p>No one has been invited.</p>
                @endforelse
            </aside>
        </div>

        <footer class="flex items-center gap-4 mt-2">
            @can('update', $booking)
                <x-button.primary :href="route('booking.edit', $booking)">
                    {{ __('Edit') }}
                </x-button.primary>
            @endcan
            @include('booking.partials.respond-button', [
                'booking' => $booking,
                'attendance' => $attendance,
            ])
            @if (
                $booking->isFuture() &&
                    !$booking->isCancelled() &&
                    app(Gate::class)->check('create', [Attendance::class, $booking]))
                <x-button.secondary :href="route('booking.attendee.invite', $booking)">
                    {{ __('Invite Attendee') }}
                </x-button.secondary>
            @endif
        </footer>
    </section>
</x-layout.app>
