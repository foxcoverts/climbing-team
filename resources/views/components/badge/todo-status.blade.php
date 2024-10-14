@use('App\Enums\TodoStatus')
@props([
    'status',
    'color' => match ($status) {
        TodoStatus::InProcess => 'sky',
        TodoStatus::Completed => 'lime',
        default => 'gray',
    },
    'icon' => match ($status) {
        TodoStatus::InProcess => 'outline.dot',
        TodoStatus::Completed => 'outline.checkmark',
        TodoStatus::Cancelled => 'outline.close',
        default => 'outline',
    },
    'label' => __('app.todo.status.' . $status->value),
])
<x-badge :$color :$icon :$label {{ $attributes }} />
