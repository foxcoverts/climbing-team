@props(['booking', 'attendance'])
<x-layout.app :title="$booking->activity . ' - ' . localDate($booking->start_at)->toFormattedDayDateString()">
    <section class="p-4 sm:p-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            <div class="w-full max-w-xl">
                @include('booking.partials.details')

                <footer class="flex items-start gap-4 mt-4">
                    @can('update', $booking)
                        <x-button.primary :href="route('booking.edit', $booking)">
                            @lang('Edit')
                        </x-button.primary>
                    @endcan
                </footer>
            </div>
            @include('booking.partials.guest-list')
        </div>
    </section>
</x-layout.app>
