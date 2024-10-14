@props(['where' => 'end', 'before' => '', 'after' => '', 'show' => 'always'])
@if ($show == 'always' || $show == 'text')
    {{ $before }}
    @switch($where)
        @case('start')
            {{ __('This task') }}{{ $after }}
        @break

        @default
            {{ __('this task') }}{{ $after }}
    @endswitch
@endif
