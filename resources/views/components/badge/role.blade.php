@use('App\Enums\Role')
@props([
    'role',
    'color' => match ($role) {
        Role::TeamLeader => 'yellow',
        Role::TeamMember => 'lime',
        Role::Suspended => 'pink',
        default => 'gray',
    },
    'icon' => null,
    'label' => __('app.user.role.' . $role->value),
])
<x-badge :$color :$icon :$label {{ $attributes }} />
