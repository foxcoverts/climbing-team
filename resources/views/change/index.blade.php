<x-layout.app :title="__('Changes')">
    <section class="p-4 sm:px-8 max-w-3xl space-y-4">
        <h1 class="text-2xl sm:text-3xl font-medium">@lang('Changes')</h1>

        <div class="space-y-2" id="changes" x-merge="append">
            @php($booking_link = 'change.partials.booking-link')
            @foreach ($changes as $change)
                <x-recent-activity.item :id="$change->id">
                    <x-slot:time>
                        <p>
                            <span title="{{ localDate($change->created_at)->toDayDateTimeString() }}" class="cursor-help">
                                {{ localDate($change->created_at)->ago() }}
                            </span>
                        </p>
                    </x-slot:time>

                    @foreach ($change->attendees as $attendee)
                        @php($attendee->change = $change)
                        @can('view', $attendee)
                            @include('change.partials.attendance')
                        @endcan
                    @endforeach

                    @foreach ($change->comments as $comment)
                        @php($comment->change = $change)
                        @php($comment->author = $change->author)
                        @can('view', $comment)
                            @include('change.partials.comment')
                        @endcan
                    @endforeach

                    @foreach ($change->fields as $field)
                        @php($field->change = $change)
                        @can('view', $field)
                            @include('change.partials.field')
                        @endcan
                    @endforeach
                </x-recent-activity.item>
            @endforeach
        </div>

        {{ $changes->links('change.partials.infinite-scroll') }}
    </section>
</x-layout.app>
