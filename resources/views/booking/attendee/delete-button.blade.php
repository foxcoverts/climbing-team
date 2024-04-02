@props(['attendee', 'booking'])
@can('delete', $attendee->attendance)
    <form method="post" action="{{ route('booking.attendee.destroy', [$booking, $attendee]) }}" x-data="{ submitted: false }"
        x-on:submit="setTimeout(() => submitted = true, 0)">
        @csrf
        @method('delete')
        <x-button.danger class="whitespace-nowrap" x-bind:disabled="submitted"
            x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Remove') }}'" />
    </form>
@endcan
