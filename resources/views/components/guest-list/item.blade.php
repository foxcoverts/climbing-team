@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@props(['booking', 'attendee', 'currentUser'])

<div x-data @click="if ($refs.link) window.location = $refs.link.href;" class="flex flex-wrap items-start gap-1"
    :class="{ 'cursor-pointer': $refs.link }">
    @can('view', $attendee->attendance)
        <a href="{{ route('booking.attendee.show', [$booking, $attendee]) }}" x-ref="link">{{ $attendee->name }}</a>
    @else
        <span>{{ $attendee->name }}</span>
    @endcan

    @if ($attendee->isPermitHolder())
        <x-badge.permit-holder class="text-xs whitespace-nowrap" />
    @endif

    @if ($attendee->isGuest())
        <x-badge.role :role="Role::Guest" class="text-xs whitespace-nowrap" />
    @endif

    @if ($attendee->isUnder18())
        <x-badge.under-18 class="text-xs whitespace-nowrap" />
    @endif

    @if ($attendee->isKeyHolder())
        @can('manage', App\Models\Key::class)
            <a href="{{ route('key.index') }}">
                <x-badge.key-holder label="" class="text-xs whitespace-nowrap" />
            </a>
        @else
            <x-badge.key-holder label="" class="text-xs whitespace-nowrap" />
        @endcan
    @endif

    @if ($attendee->is($currentUser))
        <x-badge color="lime" class="text-xs whitespace-nowrap">{{ __('You') }}</x-badge>
    @endif
</div>
