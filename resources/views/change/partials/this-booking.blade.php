@props(['where' => 'end', 'before' => '', 'after' => '', 'show' => 'always'])
@if ($show == 'always' || $show == 'text')
    {{ $before }}
    @switch($where)
        @case('start')
            {{ __('This booking') }}{{ $after }}
        @break

        @default
            {{ __('this booking') }}{{ $after }}
    @endswitch
@endif
