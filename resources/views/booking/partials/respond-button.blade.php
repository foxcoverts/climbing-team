@use('App\Enums\AttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
@props(['booking', 'attendance'])
@if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('respond', [$booking, $currentUser]))
    <div class="flex" x-data="{ open: false }">
        <div class="relative">
            <button
                class="relative focus:z-30 flex gap-2 px-4 py-2 border rounded-t-md font-semibold text-xs uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25"
                :class="{ 'rounded-b-md': !open }" x-on:click="open = true">
                <x-icon.attendance :$attendance class="w-4 h-4 fill-current" />
                @if (is_null($attendance))
                    @lang('Respond')
                @else
                    @lang("app.attendee.status.{$attendance->status->value}")
                @endif

                <x-icon.cheveron-down class="w-4 h-4 fill-current" x-show="!open" />
                <x-icon.cheveron-up class="w-4 h-4 fill-current" x-show="open" x-cloak />
            </button>

            <div x-show="open" x-cloak x-on:click.outside="open = false" x-data="{ submitted: false }"
                class="absolute left-0 z-20 shadow-xl border border-t-0 border-gray-300 dark:border-gray-500 min-w-full">
                <form method="post" action="{{ route('booking.attendance.update', $booking) }}" id="update-attendee"
                    x-on:submit="setTimeout(() => submitted = true, 0)">
                    @csrf
                    @method('PUT')
                </form>
                @can('delete', $attendance)
                    <form method="post" action="{{ route('booking.attendee.destroy', [$booking, $currentUser]) }}"
                        id="remove-attendee" x-on:submit="setTimeout(() => submitted = true, 0)">
                        @csrf
                        @method('delete')
                    </form>
                @endcan
                <div class="divide-y divide-gray-300 dark:divide-gray-500">
                    @foreach ([AttendeeStatus::Accepted, AttendeeStatus::Tentative, AttendeeStatus::Declined] as $status)
                        @if ($status != $attendance?->status)
                            <button name="status" value="{{ $status->value }}" type="submit" form="update-attendee"
                                x-bind:disabled="submitted" :class="submitted ? 'cursor-progress' : ''"
                                class="relative focus:z-30 flex gap-2 flex-nowrap items-center min-w-full px-4 py-2 text-xs uppercase font-semibold tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                                <x-icon.outline class="h-4 w-4 fill-current" />
                                <span class="flex-grow text-left">@lang("app.attendee.status.{$status->value}")</span>
                            </button>
                        @endif
                    @endforeach
                    @can('delete', $attendance)
                        <button type="submit" form="remove-attendee" x-bind:disabled="submitted"
                            :class="submitted ? 'cursor-progress' : ''"
                            class="relative focus:z-30 flex gap-2 flex-nowrap items-center min-w-full px-4 py-2 text-xs uppercase font-semibold tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 text-red-500 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                            <x-icon.outline.close class="h-4 w-4 fill-current" />
                            <span class="flex-grow text-left">@lang('Remove')</span>
                        </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@elseif ($booking->isCancelled())
    <div
        class="flex gap-2 px-4 py-2 cursor-not-allowed border rounded-md font-semibold text-xs uppercase tracking-widest bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm disabled:opacity-25">
        <x-icon.outline.close class="h-4 w-4 fill-current" />
        @lang('Cancelled')
    </div>
@elseif (!is_null($attendance))
    <div
        class="flex gap-2 px-4 py-2 cursor-not-allowed border rounded-md font-semibold text-xs uppercase tracking-widest bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm disabled:opacity-25">
        <x-icon.attendance :$attendance class="w-4 h-4 fill-current" />
        @lang("app.attendee.status.{$attendance->status->value}")
    </div>
@endif
