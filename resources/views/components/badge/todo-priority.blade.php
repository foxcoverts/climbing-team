@use('App\Enums\TodoPriority')
@props([
    'priority',
    'color' => match ($priority) {
        1, 2, 3, 4 => 'lime',
        5 => 'sky',
        6, 7, 8, 9 => 'yellow',
    },
    'icon' => null,
    'label' => __('app.todo.priority.' . $priority),
])
<x-badge :$color :$icon :$label {{ $attributes }} />
