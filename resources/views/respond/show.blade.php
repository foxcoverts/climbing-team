@use('App\Enums\AttendeeStatus')
<x-layout.guest :title="__('Invitation')">
    <div class="space-y-2">
        <div>
            <x-fake-label :value="__('When')" />
            <p>
                <span x-data="{{ Js::from(['start_at' => localDate($booking->start_at)]) }}"
                    x-text="dateString(start_at)">{{ localDate($booking->start_at)->toFormattedDayDateString() }}</span>
                {{ __('from :start_time to :end_time (:duration)', [
                    'start_time' => localDate($booking->start_at)->format('H:i'),
                    'end_time' => localDate($booking->end_at)->format('H:i'),
                    'duration' => $booking->start_at->diffAsCarbonInterval($booking->end_at),
                ]) }}
            </p>
        </div>

        <div>
            <x-fake-label :value="__('Location')" />
            <p>{{ $booking->location }}</p>
        </div>

        <div>
            <x-fake-label :value="__('Activity')" />
            <p>{{ $booking->activity }}</p>
        </div>

        @if ($booking->lead_instructor)
            <div>
                <x-fake-label :value="__('Lead Instructor')" />
                <p>{{ $booking->lead_instructor->name }}</p>
            </div>
        @endif

        <div>
            <x-fake-label :value="__('Group')" />
            <p>{{ $booking->group_name }}</p>
        </div>

        @if ($booking->notes)
            <div>
                <x-fake-label :value="__('Notes')" />
                <x-markdown :text="$booking->notes" />
            </div>
        @endif
    </div>

    <form method="post" action="{{ route('respond', [$booking, $user]) }}" x-data="{ submitted: false }"
        x-on:submit="setTimeout(() => submitted = true, 0)">
        @csrf
        <input type="hidden" name="invite" value="{{ $user->attendance->token }}" autocomplete="off" />
        <div
            class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
            <p class="text-lg text-center">@lang('Can you attend this event?')</p>
            <div class="flex justify-center gap-4">
                <x-button.primary type="submit" name="status" x-bind:disabled="submitted"
                    :value="AttendeeStatus::Accepted->value">@lang('Yes')</x-button.primary>
                <x-button.secondary type="submit" name="status" x-bind:disabled="submitted"
                    :value="AttendeeStatus::Declined->value">@lang('No')</x-button.secondary>
                <x-button.secondary type="submit" name="status" x-bind:disabled="submitted"
                    :value="AttendeeStatus::Tentative->value">@lang('Maybe')</x-button.secondary>
            </div>
            <p class="text-sm text-center">@lang('Replying for :name.', ['name' => $user->name])</p>
        </div>
    </form>
</x-layout.guest>
