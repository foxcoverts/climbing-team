@use(App\Enums\BookingStatus)
@props(['booking'])
@if ($booking->isFuture())
    @if ($booking->isCancelled())
        <form method="post" action="{{ route('booking.destroy', $booking) }}">
            @csrf
            @method('delete')

            <x-button.danger>
                {{ __('Delete') }}
            </x-button.danger>
        </form>
    @else
        <form method="post" action="{{ route('booking.update', $booking) }}">
            @csrf
            @method('patch')
            <input type="hidden" name="status" value="{{ BookingStatus::Cancelled }}" />
            <x-button.danger>
                {{ __('Cancel Booking') }}
            </x-button.danger>
        </form>
    @endif
@endif
