<x-layout.app :title="__('My Invites')">
    <section class="sm:mb-4">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <div class="px-4 sm:px-8 flex items-center justify-between">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.inbox style="height: .75lh" class="fill-current" aria-hidden="true" />
                    <span>@lang('My Invites')</span>
                </h1>
            </div>
        </header>

        @if ($invites->isNotEmpty())
            <div class="p-4 sm:px-8 space-y-2">
                <h2 class="text-xl font-medium">@lang('Invited')</h2>
                <p>@lang('You have been invited to the following bookings.')</p>
                @include('booking.partials.list', [
                    'showRoute' => 'booking.attendance.edit',
                    'bookings' => $invites,
                ])
            </div>
        @endif

        @if ($maybes->isNotEmpty())
            <div class="p-4 sm:px-8 space-y-2">
                <h2 class="text-xl font-medium">@lang('Maybe')</h2>
                <p>@lang('You have not yet confirmed that you can, or cannot, attend the following bookings.')</p>
                @include('booking.partials.list', [
                    'showRoute' => 'booking.attendance.edit',
                    'bookings' => $maybes,
                ])
            </div>
        @endif
    </section>
</x-layout.app>
