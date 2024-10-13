@use('Carbon\Carbon')
<x-layout.app :title="__('Changes')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Changes') }}
                </h1>
            </div>
        </header>

        <div class="px-4 sm:px-8 max-w-3xl space-y-2 my-4">
            <div class="space-y-2" id="changes" x-merge="append">
                @foreach ($changes as $change)
                    @switch ($change->changeable_type)
                        @case (App\Models\Booking::class)
                            @php($changeable_link = 'change.partials.booking.link')
                            @php($attendee_link = 'change.partials.booking.attendee-link')
                        @break

                        @case (App\Models\Todo::class)
                            @php($changeable_link = 'change.partials.todo.link')
                        @break

                        @default
                            @continue(2)
                    @endswitch

                    <x-recent-activity.item :id="$change->id">
                        <x-slot:time>
                            <p><span x-data="{{ Js::from(['start_at' => localDate($change->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
                                    class="cursor-help">{{ ucfirst(localDate($change->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS])) }}</span>
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
