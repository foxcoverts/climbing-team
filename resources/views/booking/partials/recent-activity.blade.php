@use('App\Enums\AttendeeStatus')
@use('Carbon\Carbon')
@use('Illuminate\Support\Str')
<section class="mt-6">
    <h3 class="text-xl font-medium">@lang('Recent Activity')</h3>

    <div class="space-y-2">
        @include('booking.partials.comment-form')

        @php($changed_attendees = [])
        @php($changed_fields = [])
        @foreach ($booking->changes as $change)
            @php($change->booking = $booking)
            <x-recent-activity.item :id="$change->id">
                <x-slot:time>
                    <p><span title="{{ localDate($change->created_at)->toDayDateTimeString() }}" class="cursor-help">
                            {{ localDate($change->created_at)->ago() }}
                        </span></p>
                </x-slot:time>

                @foreach ($change->attendees as $attendee)
                    @php($attendee->change = $change)
                    @can('view', $attendee)
                        @unless ($changed_attendees[$attendee->attendee_id] ?? false)
                            <div class="border-l-2 ml-2 pl-2" id="{{ $attendee->id }}">
                                <p><a href="{{ route('user.show', $attendee->attendee) }}"
                                        class="font-medium">{{ $attendee->attendee->name }}</a>
                                    @switch ($attendee->attendee_status)
                                        @case(AttendeeStatus::Accepted)
                                            @lang('will be going to this booking.')
                                        @break

                                        @case(AttendeeStatus::Tentative)
                                            @lang('may be able to attend this booking.')
                                        @break

                                        @case(AttendeeStatus::Declined)
                                            @lang('cannot attend this booking.')
                                        @break
                                    @endswitch
                                </p>
                                @if ($attendee->attendee_comment)
                                    <div><a href="{{ route('user.show', $change->author) }}"
                                            class="font-medium">{{ $change->author->name }}</a> @lang('commented')</div>
                                    <p class="border-l-2 ml-2 pl-2">{{ $attendee->attendee_comment }}</p>
                                @endif
                            </div>
                        @endunless
                        @php($changed_attendees[$attendee->attendee_id] = true)
                    @endcan
                @endforeach
                @foreach ($change->comments as $comment)
                    @php($comment->change = $change)
                    @can('view', $comment)
                        <div class="border-l-2 ml-2 pl-2" id="{{ $comment->id }}">
                            <div><a href="{{ route('user.show', $change->author) }}"
                                    class="font-medium">{{ $change->author->name }}</a> @lang('commented')</div>
                            <p class="border-l-2 ml-2 pl-2">{{ $comment->body }}</p>
                        </div>
                    @endcan
                @endforeach
                @foreach ($change->fields as $field)
                    @php($field->change = $change)
                    @can('view', $field)
                        @if ($changed_fields[$field->name] ?? false)
                            {{-- skip --}}
                        @elseif ($field->name == 'status' && $field->value == 'tentative')
                            <div class="border-l-2 ml-2 pl-2">@lang('Booking :status.', ['status' => 'restored'])</div>
                        @elseif ($field->name == 'status')
                            <div class="border-l-2 ml-2 pl-2">@lang('Booking :status.', ['status' => $field->value])</div>
                        @endif
                        @php($changed_fields[$field->name] = true)
                    @endcan
                @endforeach
            </x-recent-activity.item>
        @endforeach
        <div>
            <p><span title="{{ localDate($booking->created_at)->toDayDateTimeString() }}" class="cursor-help">
                    {{ localDate($booking->created_at)->ago() }}
                </span></p>
            <div class="border-l-2 ml-2 pl-2">@lang('Booking created.')</div>
        </div>
    </div>
</section>
