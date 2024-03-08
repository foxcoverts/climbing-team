@use('App\Enums\BookingStatus')
@props(['booking'])
@if ($booking->isCancelled())
    @can('delete', $booking)
        <form method="post" action="{{ route('booking.destroy', $booking) }}">
            @csrf
            @method('delete')

            <x-button.danger>
                @lang('Delete')
            </x-button.danger>
        </form>
    @endcan
@elseif ($booking->isFuture())
    @can('update', $booking)
        <form method="post" action="{{ route('booking.update', $booking) }}">
            @csrf
            @method('patch')
            <input type="hidden" name="status" value="{{ BookingStatus::Cancelled }}" />
            <x-button.danger>
                @lang('Cancel')
            </x-button.danger>
        </form>
    @endcan
@endif
