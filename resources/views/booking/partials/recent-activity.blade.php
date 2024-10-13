@use('App\Enums\AttendeeStatus')
@use('Carbon\Carbon')
@use('Illuminate\Support\Str')
<section>
    <div class="border-b border-gray-800 dark:border-gray-200">
        <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('Recent Activity') }}</h3>
    </div>

    <div class="space-y-2 mt-2">
        @include('booking.partials.comment-form')

        @php($changeable_link = 'change.partials.booking.this')
        @php($attendee_link = 'change.partials.booking.attendee-link')
        @php($changed_attendees = [])
        @php($changed_fields = [])
        @foreach ($booking->changes as $change)
            @php($change->changeable = $booking)
            <x-recent-activity.item :id="$change->id">
                <x-slot:time>
                    <p><span x-data="{{ Js::from(['start_at' => localDate($change->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
                            class="cursor-help">{{ ucfirst(localDate($change->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS])) }}</span>
                    </p>
                </x-slot:time>

                @foreach ($change->attendees as $attendee)
                    @php($attendee->change = $change)
                    @can('view', $attendee)
                        @unless ($changed_attendees[$attendee->attendee_id] ?? false)
                            @include('change.partials.attendance')
                        @endunless
                        @php($changed_attendees[$attendee->attendee_id] = true)
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
                        @unless ($changed_fields[$field->name] ?? false)
                            @include('change.partials.field')
                        @endunless
                        @php($changed_fields[$field->name] = true)
                    @endcan
                @endforeach
            </x-recent-activity.item>
        @endforeach
        <div>
            <p><span x-data="{{ Js::from(['start_at' => localDate($booking->created_at, $booking->timezone)]) }}" x-bind:title="dateTimeString(start_at)"
                    class="cursor-help">{{ ucfirst(localDate($booking->created_at, $booking->timezone)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS])) }}</span>
            </p>
            <div class="border-l-2 ml-2 pl-2">{{ __('This booking was created.') }}</div>
        </div>
    </div>
</section>
