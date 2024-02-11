<x-layout.app :title="__('booking.title')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div
                class="p-4 sm:p-8 bg-white text-gray-900 dark:text-gray-100 dark:bg-gray-800 shadow sm:rounded-lg space-y-4">
                <section>
                    @include('booking.partials.header', ['booking' => $booking])
                    <div class="md:flex md:space-x-2">
                        @include('booking.partials.details', ['booking' => $booking])

                        <aside class="my-2 flex-grow max-w-xl">
                            <h2 class="text-xl font-semibold border-b border-gray-800 dark:border-gray-200">
                                {{ __('Guest list') }}
                            </h2>

                            @forelse ($booking->attendees->groupBy('attendance.status') as $status => $attendees)
                                <h3 class="text-lg">{{ __("attendee.status.$status") }}</h3>
                                <ul>
                                    @foreach ($attendees as $attendee)
                                        <li><a href="{{ route('user.show', $attendee) }}">{{ $attendee->name }}</a></li>
                                    @endforeach
                                </ul>
                            @empty
                                <p>No one has been invited yet.</p>
                            @endforelse
                        </aside>
                    </div>

                    <footer class="flex items-center gap-4 my-2">
                        <x-button.primary :href="route('booking.edit', $booking)">
                            {{ __('Edit') }}
                        </x-button.primary>
                        <form method="post" action="{{ route('booking.destroy', $booking) }}">
                            @csrf
                            @method('delete')

                            <x-button.danger>
                                {{ __('Delete') }}
                            </x-button.danger>
                        </form>
                        @include('booking.partials.respond-button', [
                            'booking' => $booking,
                            'attendance' => $attendance,
                        ])
                    </footer>
                </section>
            </div>
        </div>
    </div>
</x-layout.app>
