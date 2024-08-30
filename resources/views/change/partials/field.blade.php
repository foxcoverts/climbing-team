@if ($field->name == 'status' && $field->value == 'tentative')
    <div class="border-l-2 ml-2 pl-2">
        @include($changeable_link, [
            'changeable' => $change->changeable,
            'where' => 'start',
        ])
        {{ __('was :status.', ['status' => 'restored']) }}
    </div>
@elseif ($field->name == 'status')
    <div class="border-l-2 ml-2 pl-2">
        @include($changeable_link, [
            'changeable' => $change->changeable,
            'where' => 'start',
        ])
        {{ __('was :status.', ['status' => $field->value]) }}
    </div>
@endif
