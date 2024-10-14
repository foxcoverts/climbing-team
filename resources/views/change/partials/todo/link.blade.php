@props(['where' => 'end', 'before' => '', 'after' => '', 'show' => 'always'])
@if ($show == 'always' || $show == 'link')
    {{ $before }}
    <a href="{{ route('todo.show', $changeable) }}"
        class="font-medium truncate">{{ $changeable->summary }}</a>{{ $after }}
@endif
