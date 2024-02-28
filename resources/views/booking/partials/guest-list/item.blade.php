@use(App\Enums\Accreditation)
@use(App\Enums\Role)
@props(['booking', 'attendee'])

@can('view', $attendee->attendance)
    <a href="{{ route('booking.attendee.show', [$booking, $attendee]) }}">{{ $attendee->name }}</a>
@else
    <span>{{ $attendee->name }}</span>
@endcan

@if ($attendee->accreditations->contains(Accreditation::PermitHolder))
    <x-badge.accreditation :accreditation="Accreditation::PermitHolder" class="text-xs" />
@endif

@if ($attendee->isGuest())
    <x-badge.role :role="Role::Guest" class="text-xs" />
@endif
