@if ($field->name == 'status')
    @switch ($field->value)
        @case('needs-action')
            @php($status = 'paused')
        @break

        @case('tentative')
            @php($status = 'restored')
        @break

        @case('in-process')
            @php($status = 'started')
        @break

        @default
            @php($status = $field->value)
    @endswitch

    <div class="border-l-2 ml-2 pl-2">
        @include($changeable_link, [
            'changeable' => $change->changeable,
            'where' => 'start',
        ])
        {{ __('was :status by :author.', ['status' => $status, 'author' => $change->author->name]) }}
    </div>
@elseif ($field->name == 'location')
    <div class="border-l-2 ml-2 pl-2">
        @include($changeable_link, [
            'changeable' => $change->changeable,
            'where' => 'start',
        ])
        {{ __('will now take place at :location.', ['location' => $field->value]) }}
    </div>
@elseif ($field->name == 'start_at' || $field->name == 'end_at')
    @switch($field->name)
        @case('start_at')
            @php($status = 'start')
        @break

        @case('end_at')
            @php($status = 'finish')
        @break
    @endswitch
    @php($localDateTime = localDate($field->value, $change->changeable->timezone))
    <div class="border-l-2 ml-2 pl-2">
        @include($changeable_link, [
            'changeable' => $change->changeable,
            'where' => 'start',
        ])
        will now {{ $status }} on
        <span x-data="{{ Js::from(['start_at' => $localDateTime]) }}"
            x-text="dateString(start_at)">{{ $localDateTime->toFormattedDayDateString() }}</span>
        {{ __('at :start_time.', [
            'start_time' => $localDateTime->format('H:i'),
        ]) }}
    </div>
@endif
