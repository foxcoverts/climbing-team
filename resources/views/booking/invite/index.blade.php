@use('Carbon\Carbon')
<x-layout.app :title="__('Booking Invites')">
    <section class="p-4 sm:px-8 grow">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Booking Invites')
            </h2>
        </header>

        <table class="w-full mt-6 text-gray-700 dark:text-gray-300 border border-gray-300">
            @forelse ($bookings as $day => $list)
                <thead>
                    <tr class="border border-gray-300">
                        <th
                            class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                            {{ Carbon::parse($day)->toFormattedDayDateString() }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($list as $booking)
                        <tr @class([
                            'border border-gray-300 group',
                            'fc-event-tentative' => $booking->isTentative(),
                        ])>
                            <td class="text-left p-0">
                                <a href="{{ route('booking.show', $booking) }}"
                                    class="block px-3 py-2 hover:bg-opacity-15 hover:bg-gray-900 hover:text-black dark:hover:bg-white dark:hover:text-white">
                                    <span class="mr-4">{{ $booking->startTime }} - {{ $booking->endTime }}</span>
                                    <span class="group-hover:underline">@lang(':activity for :group at :location', [
                                        'activity' => $booking->activity,
                                        'group' => $booking->group_name,
                                        'location' => $booking->location,
                                    ])</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            @empty
                <tbody>
                    <tr @class(['border border-gray-300 group'])>
                        <td class="text-left px-3 py-2">
                            @lang('No invites to display.')
                        </td>
                    </tr>
                </tbody>
            @endforelse
        </table>
    </section>
</x-layout.app>
