@use('App\Enums\BookingStatus')
@props(['booking'])
@if ($booking->isCancelled())
    @can('delete', $booking)
        <x-button.danger x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-booking-deletion')"
            :label="__('Permanently Delete')" />

        <x-modal name="confirm-booking-deletion" :show="$errors->bookingDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('booking.destroy', $booking) }}" class="p-6"
                x-data="{ submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                @csrf
                @method('DELETE')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Are you sure you want to delete this booking?') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Once this booking is deleted, all of its resources and data will be permanently deleted.') }}
                </p>

                <div class="mt-6">
                    <x-input-label for="confirm">
                        {{ __('Please type "DELETE" to confirm you would like to permanently delete this booking.') }}
                    </x-input-label>

                    <x-text-input id="confirm" name="confirm" type="text" class="mt-1 block w-3/4" :value="old('confirm')"
                        autocapitalize="characters" autofocus required />

                    <x-input-error :messages="$errors->bookingDeletion->get('confirm')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <x-button.secondary x-on:click="$dispatch('close')" :label="__('Back')" />

                    <x-button.danger x-bind:disabled="submitted" :label="__('Permanently Delete')"
                        x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Permanently Delete') }}'" />
                </div>
            </form>
        </x-modal>
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
