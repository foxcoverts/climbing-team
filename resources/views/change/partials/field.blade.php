@if ($field->name == 'status')
    @switch ($field->value)
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
@endif
