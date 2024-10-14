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
            start: new Date,
            message: '',
            timer: null,
            tick() {
                var remaining = 5 - Math.floor((new Date - this.start) / 1000);
                if (remaining <= 0) {
                    clearInterval(this.timer);
                    this.message = {{ Js::from('Saving your response!') }};
                    this.$root.requestSubmit();
                } else if (remaining == 1) {
                    this.message = {{ Js::from('Saving in 1 second') }};
                } else {
                    this.message = {{ Js::from('Saving in REMAINING seconds') }}.replace('REMAINING', remaining);
                }
            },
            init() {
                this.timer = setInterval(() => this.tick(), 500);
                this.tick();
            }
        }">
            @csrf
            <input type="hidden" name="invite" value="{{ $user->attendance->token }}" />
            <input type="hidden" name="sequence" value="{{ $booking->sequence }}" />
            <input type="hidden" name="status" value="{{ $status->value }}" />

            <div class="space-y-4">
                <h2 class="text-lg text-center">
                    {{ __('Please wait...') }}
                </h2>
                <p class="text-md text-center" x-text="message">&nbsp;</p>
            </div>
        </form>
    </div>
</x-layout.guest>
