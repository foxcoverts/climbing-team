@use('App\Enums\AttendeeStatus')
@props(['status'])

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

<x-badge :color="$color" :icon="$icon" {{ $attributes }}>
    @lang("app.attendee.status.{$status->value}")
</x-badge>
