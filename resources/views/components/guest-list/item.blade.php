@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@props(['booking', 'attendee'])

<div x-data @click="if ($refs.link) window.location = $refs.link.href;" :class="{ 'cursor-pointer': $refs.link }">
    @can('view', $attendee->attendance)
        <a href="{{ route('booking.attendee.show', [$booking, $attendee]) }}" x-ref="link">{{ $attendee->name }}</a>
    @else
        <span>{{ $attendee->name }}</span>
    @endcan

    @if ($attendee->isPermitHolder())
        <x-badge.accreditation :accreditation="Accreditation::PermitHolder" class="text-xs" />
    @endif

    @if ($attendee->isGuest())
        <x-badge.role :role="Role::Guest" class="text-xs" />
    @endif

    @if ($attendee->isUnder18())
        <x-badge.under-18 class="text-xs" />
    @endif

    @if ($attendee->is(auth()->user()))
        <x-badge color="lime" class="text-xs">@lang('You')</x-badge>
    @endif
</div>
