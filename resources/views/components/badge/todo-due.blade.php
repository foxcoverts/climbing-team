@props([
    'todo',
    ...$todo->isOverdue()
        ? [
            'color' => 'pink',
            'icon' => 'outline.exclamation',
        ]
        : [
            'color' => null,
            'icon' => null,
        ],
])
@if (!empty($todo->due_at))
    <x-badge :$color :$icon :label="__('app.todo.due_ago', ['ago' => localDate($todo->due_at)->ago()])" :title="__('app.todo.due_date', ['date' => localDate($todo->due_at)->toDayDateTimeString()])" {{ $attributes }} />
@endif
