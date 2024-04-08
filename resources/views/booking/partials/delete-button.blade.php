@use('App\Enums\BookingStatus')
@props(['booking'])
@if ($booking->isCancelled())
    @can('delete', $booking)
        <form method="post" action="{{ route('booking.destroy', $booking) }}" x-data="{ submitted: false }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf
            @method('delete')

            <x-button.danger x-bind:disabled="submitted" :label="__('Delete')"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Delete') }}'" />
        </form>
    @endcan
@elseif ($booking->isFuture())
    @can('update', $booking)
        <form method="post" action="{{ route('booking.update', $booking) }}" x-data="{ submitted: false }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf
            @method('patch')
            <input type="hidden" name="status" value="{{ BookingStatus::Cancelled }}" />

            <x-button.danger x-bind:disabled="submitted" :label="__('Cancel')"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Cancel') }}'" />
        </form>
    @endcan
@endif
