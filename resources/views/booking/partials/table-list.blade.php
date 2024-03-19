@use('Carbon\Carbon')
@props(['showRoute' => 'booking.show'])
<table class="w-full mt-6 text-gray-700 dark:text-gray-300 border border-gray-300">
    @forelse ($bookings as $day => $list)
        <thead>
            <tr class="border border-gray-300">
                <th
                    class="px-3 py-2 font-medium text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                    <span x-data="{{ Js::from(['date' => localDate($day)]) }}" x-text="dateString(date)"></span>
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $booking)
                <tr @class([
                    'border border-gray-300 group',
                    'fc-event-tentative' => $booking->isTentative(),
                ])>
                    <td class="text-left p-0">
                        <a href="{{ route($showRoute, $booking) }}"
                            class="block px-3 py-2 hover:bg-opacity-15 hover:bg-gray-900 hover:text-black dark:text-gray-100 dark:hover:bg-opacity-15 dark:hover:bg-white dark:hover:text-white">
                            <span class="mr-4">{{ localDate($booking->start_at)->format('H:i') }} -
                                {{ localDate($booking->end_at)->format('H:i') }}</span>
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
                    @lang('No bookings to display.')
                </td>
            </tr>
        </tbody>
    @endforelse
</table>
