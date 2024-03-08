@use('App\Enums\AttendeeStatus')
<x-layout.guest :title="__('Invitation')">
    <div class="space-y-2">
        <p><dfn class="block not-italic font-medium">@lang('When')</dfn>
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

        <p><dfn class="block not-italic font-medium">@lang('Location')</dfn>
            {{ $booking->location }}
        </p>

        <p><dfn class="block not-italic font-medium">@lang('Activity')</dfn>
            {{ $booking->activity }}
        </p>

        @if ($booking->lead_instructor)
            <p><dfn class="block not-italic font-medium">@lang('Lead Instructor')</dfn>
                {{ $booking->lead_instructor->name }}
            </p>
        @endif

        <p><dfn class="block not-italic font-medium">@lang('Group')</dfn>
            {{ $booking->group_name }}
        </p>

        @if ($booking->notes)
            <div><dfn class="block not-italic font-medium">@lang('Notes')</dfn>
                <x-markdown :text="$booking->notes" />
            </div>
        @endif
    </div>

    <form method="post" action="{{ url()->full() }}">
        @csrf
        <div
            class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
            <p class="text-lg text-center">@lang('Can you attend this event?')</p>
            <div class="flex justify-center gap-4">
                <x-button.primary type="submit" name="status" :value="AttendeeStatus::Accepted->value">@lang('Yes')</x-button.primary>
                <x-button.secondary type="submit" name="status"
                    :value="AttendeeStatus::Declined->value">@lang('No')</x-button.secondary>
                <x-button.secondary type="submit" name="status"
                    :value="AttendeeStatus::Tentative->value">@lang('Maybe')</x-button.secondary>
            </div>
            <p class="text-sm text-center">@lang('Replying for :name.', ['name' => $user->name])</p>
        </div>
    </form>

</x-layout.guest>
