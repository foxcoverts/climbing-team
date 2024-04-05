@use('App\Enums\AttendeeStatus')
@props(['status'])

@if (is_string($status))
    @php($status = AttendeeStatus::tryFrom($status))
@endif

@switch($status)
    @case(AttendeeStatus::NeedsAction)
        @php($icon = 'inbox')
        @php($color = 'sky')
    @break

    @case(AttendeeStatus::Tentative)
        @php($icon = 'inbox')
        @php($color = 'yellow')
    @break

    @case(AttendeeStatus::Accepted)
        @php($icon = 'inbox.check')
        @php($color = 'lime')
    @break

    @default
        @php($icon = 'inbox.cross')
        @php($color = 'pink')
@endswitch

<x-badge
    {{ $attributes->merge([
        'color' => $color,
        'icon' => $icon,
        'label' => __('app.attendee.status.' . $status->value),
    ]) }} />
