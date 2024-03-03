@use('App\Enums\AttendeeStatus')
<x-layout.guest :title="__('Invitation')">
    <div class="space-y-2">
        <p><dfn class="block not-italic font-medium">{{ __('When') }}</dfn>
            @if (localDate($booking->start_at)->isSameDay(localDate($booking->end_at)))
                {{ __(':start_date from :start_time to :end_time', [
                    'start_time' => localDate($booking->start_at)->format('H:i'),
                    'start_date' => localDate($booking->start_at)->toFormattedDayDateString(),
                    'end_time' => localDate($booking->end_at)->format('H:i'),
                ]) }}
            @else
                {{ __(':start to :end', [
                    'start' => localDate($booking->start_at)->toDayDateTimeString(),
                    'end' => localDate($booking->end_at)->toDayDateTimeString(),
                ]) }}
            @endif
        </p>

        <p><dfn class="block not-italic font-medium">{{ __('Location') }}</dfn>
            {{ $booking->location }}
        </p>

        <p><dfn class="block not-italic font-medium">{{ __('Activity') }}</dfn>
            {{ $booking->activity }}
        </p>

        <p><dfn class="block not-italic font-medium">{{ __('Group') }}</dfn>
            {{ $booking->group_name }}
        </p>

        @if ($booking->notes)
            <div><dfn class="block not-italic font-medium">{{ __('Notes') }}</dfn>
                <x-markdown :text="$booking->notes" />
            </div>
        @endif
    </div>

    <form method="post" action="{{ url()->full() }}">
        @csrf
        <div
            class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
            <p class="text-lg text-center">{{ __('Can you attend this event?') }}</p>
            <div class="flex justify-center gap-4">
                <x-button.primary type="submit" name="status"
                    :value="AttendeeStatus::Accepted->value">{{ __('Yes') }}</x-button.primary>
                <x-button.secondary type="submit" name="status"
                    :value="AttendeeStatus::Declined->value">{{ __('No') }}</x-button.secondary>
                <x-button.secondary type="submit" name="status"
                    :value="AttendeeStatus::Tentative->value">{{ __('Maybe') }}</x-button.secondary>
            </div>
            <p class="text-sm text-center">{{ __('Replying for :name.', ['name' => $user->name]) }}</p>
        </div>
    </form>

</x-layout.guest>
