@use('App\Enums\BookingStatus')
<x-layout.app :title="__(':Status Bookings', ['status' => $status->value])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    @switch ($status)
                        @case(BookingStatus::Confirmed)
                            <x-icon.calendar.check style="height: .75lh" class="fill-current" aria-hidden="true" />
                            <span>{{ __('Confirmed Bookings') }}</span>
                        @break

                        @case(BookingStatus::Tentative)
                            <x-icon.calendar.tee style="height: .75lh" class="fill-current" aria-hidden="true" />
                            <span>{{ __('Tentative Bookings') }}</span>
                        @break

                        @case(BookingStatus::Cancelled)
                            <x-icon.calendar.cross style="height: .75lh" class="fill-current" aria-hidden="true" />
                            <span>{{ __('Cancelled Bookings') }}</span>
                        @break

                        @default
                            <x-icon.calendar style="height: .75lh" class="fill-current" aria-hidden="true" />
                            <span>{{ __('Bookings') }}</span>
                    @endswitch
                </h1>
            </div>
        </header>

        <div class="p-4 sm:px-8 sm:my-4">
            @include('booking.partials.list', [
                'showRoute' => 'booking.show',
            ])
        </div>
    </section>
</x-layout.app>
