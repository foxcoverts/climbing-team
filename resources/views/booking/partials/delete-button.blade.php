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
        <x-button.danger :href="route('booking.cancel', $booking)" :label="__('Cancel')" x-target="cancel-booking"
            @ajax:before="$dispatch('dialog:open:cancel-booking')" />

        <dialog x-init @dialog:open:cancel-booking.window="$el.showModal()" @ajax:success="$el.close()"
            @click="if ($event.target === $el) $el.close()" class="bg-white dark:bg-gray-900 p-4">
            <form id="cancel-booking"></form>
        </dialog>
    @endcan
@endif
