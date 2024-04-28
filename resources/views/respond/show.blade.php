@use('App\Enums\AttendeeStatus')
@php($localStartAt = localDate($booking->start_at, $booking->timezone))
@php($localEndAt = localDate($booking->end_at, $booking->timezone))
<x-layout.guest :title="__('Invitation')">
    <div class="space-y-2">
        <div>
            <x-fake-label :value="__('When')" />
            <p>
                <span x-data="{{ Js::from(['start_at' => $localStartAt]) }}"
                    x-text="dateString(start_at)">{{ $localStartAt->toFormattedDayDateString() }}</span>
                {{ __('from :start_time to :end_time (:duration)', [
                    'start_time' => $localStartAt->format('H:i'),
                    'end_time' => $localEndAt->format('H:i'),
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

    <div
        class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
        <p class="text-lg text-center">@lang('Can you attend this event?')</p>
        <div class="flex justify-center gap-4">
            <x-button.primary :href="route('respond.accept', [$booking, $user, 'invite' => $user->attendance->token])" :label="__('Yes')" />
            <x-button.secondary :href="route('respond.decline', [$booking, $user, 'invite' => $user->attendance->token])" :label="__('No')" />
            <x-button.secondary :href="route('respond.tentative', [$booking, $user, 'invite' => $user->attendance->token])" :label="__('Maybe')" />
        </div>
        <p class="text-sm text-center">@lang('Replying for :name.', ['name' => $user->name])</p>
    </div>
</x-layout.guest>
