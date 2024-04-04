@props(['where' => 'end', 'before' => '', 'after' => '', 'show' => 'always'])
@if ($show == 'always' || $show == 'text')
    {{ $before }}
    @switch($where)
        @case('start')
            @lang('This booking'){{ $after }}
        @break

        @default
            @lang('this booking'){{ $after }}
    @endswitch
@endif
