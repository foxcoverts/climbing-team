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
                                <p><a href="{{ route('booking.attendee.show', [$booking, $attendee->attendee]) }}"
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
                                    <div><a href="{{ route('booking.attendee.show', [$booking, $change->author]) }}"
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
                    @php($comment->author = $change->author)
                    @can('view', $comment)
                        <div class="border-l-2 ml-2 pl-2" id="{{ $comment->id }}" x-data="{ menu: false, editing: false, deleting: false, submitted: false }">
                            <div><a href="{{ route('booking.attendee.show', [$booking, $change->author]) }}"
                                    class="font-medium">{{ $change->author->name }}</a> @lang('commented')</div>
                            <div class="border-l-2 ml-2 pl-2 flex gap-2" x-show="!editing">
                                <p>{{ $comment->body }}
                                    @if ($comment->created_at != $comment->updated_at)
                                        <em class="not-italic text-sm text-gray-600 dark:text-gray-400">Edited</em>
                                    @endif
                                </p>
                                @canany(['update', 'delete'], $comment)
                                    <div class="relative">
                                        <button x-show="!editing && !deleting"
                                            class="hover:bg-gray-200 dark:hover:bg-gray-700 p-1"
                                            :class="{
                                                'rounded-xl border border-transparent': !menu,
                                                'rounded-t-xl border border-b-0 bg-gray-100 border-gray-300 dark:border-gray-400 dark:bg-gray-900': menu,
                                            }"
                                            @click="menu = !menu"><x-icon.more class="w-4 h-4 fill-current transition-transform"
                                                ::class="{ 'rotate-90': menu }" /></button>
                                        <div x-cloak x-show="menu" @click.outside="menu = false"
                                            class="absolute right-0 -mt-1 z-40 text-gray-900 bg-gray-100 dark:text-gray-100 dark:bg-gray-900 shadow-sm dark:shadow-gray-400 border border-gray-300 dark:border-gray-400 divide-y divide-gray-200 dark:divide-gray-700">
                                            @can('update', $comment)
                                                <button type="button" @click="editing = true; menu = false"
                                                    class="whitespace-nowrap block hover:bg-gray-200 dark:hover:bg-gray-700 py-1 px-2 w-full text-left">@lang('Edit Comment')</button>
                                            @endcan
                                            @can('delete', $comment)
                                                <button type="button" @click="deleting = true; menu = false"
                                                    class="whitespace-nowrap block hover:bg-gray-200 dark:hover:bg-gray-700 py-1 px-2 w-full text-left">@lang('Delete Comment')</button>
                                            @endcan
                                        </div>
                                    </div>
                                @endcanany
                            </div>
                            @can('update', $comment)
                                <div x-cloak x-show="editing" x-trap="editing" @keyup.escape="editing = false">
                                    <form method="POST" action="{{ route('comment.update', $comment) }}"
                                        @submit="setTimeout(() => submitted = true, 0)">
                                        @csrf
                                        @method('PUT')
                                        <div class="flex items-stretch mt-1">
                                            <x-text-input id="body" name="body" required :value="$comment->body"
                                                class="flex-grow flex-shrink rounded-r-none" x-bind:readonly="submitted" />
                                            <x-button.primary class="rounded-l-none" x-bind:disabled="submitted">
                                                <x-icon.save class="w-4 h-4 fill-current" x-show="!submitted" />
                                                <x-icon.loading class="w-4 h-4 fill-gray-400 dark:fill-gray-600 animate-spin"
                                                    fill="none" x-cloak x-show="submitted" />
                                            </x-button.primary>
                                        </div>
                                    </form>
                                </div>
                            @endcan
                            @can('delete', $comment)
                                <div x-cloak x-show="deleting" x-trap="deleting" @keyup.escape="deleting = false">
                                    <form method="POST" action="{{ route('comment.destroy', $comment) }}"
                                        @submit="setTimeout(() => submitted = true, 0)">
                                        @csrf
                                        @method('DELETE')
                                        <p class="inline-flex">@lang('Delete this comment?')</p>
                                        <x-button.danger x-bind:disabled="submitted"
                                            x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Yes, delete') }}'" />
                                        <x-button.secondary x-show="!submitted"
                                            @click="deleting = false">@lang('Cancel')</x-button.secondary>
                                    </form>
                                </div>
                            @endcan
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
