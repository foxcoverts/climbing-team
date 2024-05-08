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
        <form action="{{ route('respond.store', [$booking, $user]) }}" method="POST" x-data="{
            init() {
                this.$root.requestSubmit();
            }
        }">
            @csrf
            <input type="hidden" name="invite" value="{{ $user->attendance->token }}" />
            <input type="hidden" name="sequence" value="{{ $booking->sequence }}" />
            <input type="hidden" name="status" value="{{ $status->value }}" />

            <div class="space-y-4">
                <p class="text-lg text-center" x-text="{{ Js::from(__('Saving your response...')) }}">
                    {{ __('Please wait...') }}
                </p>
            </div>
        </form>
    </div>
</x-layout.guest>
