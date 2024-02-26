@use('App\Enums\Role')
@props(['role'])

@switch($role)
    @case(Role::TeamLeader)
        @php($color = 'yellow')
    @break

    @case(Role::TeamMember)
        @php($color = 'lime')
    @break

    @default
        @php($color = 'gray')
@endswitch

<x-badge :color="$color">{{ __("app.user.role.{$role->value}") }}</x-badge>
