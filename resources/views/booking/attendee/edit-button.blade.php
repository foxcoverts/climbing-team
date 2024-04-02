@use('App\Enums\AttendeeStatus')
@use('Illuminate\Contracts\Auth\Access\Gate')
@props(['attendee', 'booking'])
@php($attendance = $attendee->attendance)

@if ($booking->isFuture() && !$booking->isCancelled() && app(Gate::class)->check('update', $attendance))
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

            <div x-show="open" x-cloak x-on:click.outside="open = false"
                class="absolute left-0 z-20 shadow-xl border border-t-0 border-gray-300 dark:border-gray-500 min-w-full">
                <form method="post" action="{{ route('booking.attendee.update', [$booking, $attendee]) }}"
                    x-data="{ submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                    @csrf
                    @method('PUT')
                    <div class="divide-y divide-gray-300 dark:divide-gray-500">
                        @foreach ([AttendeeStatus::Accepted, AttendeeStatus::Tentative, AttendeeStatus::Declined] as $status)
                            @if ($status != $attendance?->status)
                                <button name="status" value="{{ $status->value }}" type="submit"
                                    x-bind:disabled="submitted"
                                    class="relative focus:z-30 flex gap-2 flex-nowrap items-center min-w-full px-4 py-2 text-xs uppercase font-semibold tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                                    <x-icon.empty-outline class="h-4 w-4 fill-current" />
                                    <span class="flex-grow text-left">@lang("app.attendee.status.{$status->value}")</span>
                                </button>
                            @endif
                        @endforeach
                    </div>
                </form>
            </div>
        </div>
    </div>
@elseif (!is_null($attendance))
    <div
        class="flex gap-2 px-4 py-2 cursor-not-allowed border rounded-md font-semibold text-xs uppercase tracking-widest bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 shadow-sm disabled:opacity-25">
        <x-icon.attendance :$attendance class="w-4 h-4 fill-current" />
        @if ($attendee->id === $booking->lead_instructor_id)
            @lang('Lead Instructor')
        @else
            @lang("app.attendee.status.{$attendance->status->value}")
        @endif
    </div>
@endif
