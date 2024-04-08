@use('Carbon\Carbon')
<x-layout.app :title="__('Changes')">
    <section>
        <header class="p-4 sm:px-8 bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <h1 class="text-2xl font-medium">@lang('Changes')</h1>
        </header>

        <div class="px-4 sm:px-8 max-w-3xl space-y-2 my-4">
            <div class="space-y-2" id="changes" x-merge="append">
                @php($booking_link = 'change.partials.booking-link')
                @foreach ($changes as $change)
                    <x-recent-activity.item :id="$change->id">
                        <x-slot:time>
                            <p><span x-data="{{ Js::from(['start_at' => localDate($change->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
                                    class="cursor-help">{{ localDate($change->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}</span>
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

            {{ $changes->links('infinite-scroll', ['targets' => 'changes', 'loading' => 'change.partials.loading']) }}
        </div>
    </section>
</x-layout.app>
