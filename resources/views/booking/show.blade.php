@props(['booking', 'attendance'])
<x-layout.app :title="$booking->activity . ' - ' . localDate($booking->start_at)->toFormattedDayDateString()">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            <div class="w-full max-w-xl">
                @include('booking.partials.details')

                @include('booking.partials.recent-activity')

                @can('update', $booking)
                    <footer class="flex items-start gap-4 mt-4">
                        <x-button.primary :href="route('booking.edit', $booking)">
                            @lang('Edit')
                        </x-button.primary>
                    </footer>
                @endcan
            </div>
            @include('booking.partials.guest-list')
        </div>
    </section>
</x-layout.app>
