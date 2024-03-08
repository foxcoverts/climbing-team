@can('forceDelete', $booking)
    <x-button.danger x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-booking-deletion')">@lang('Permanently Delete')</x-button.danger>

    <x-modal name="confirm-booking-deletion" :show="$errors->bookingDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('trash.booking.destroy', $booking) }}" class="p-6">
            @csrf
            @method('DELETE')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                @lang('Are you sure you want to delete this booking?')
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                @lang('Once this booking is deleted, all of its resources and data will be permanently deleted.')
            </p>

            <div class="mt-6">
                <x-input-label for="confirm">
                    @lang('Please type "DELETE" to confirm you would like to permanently delete this booking.')
                </x-input-label>

                <x-text-input id="confirm" name="confirm" type="text" class="mt-1 block w-3/4" :value="old('confirm')"
                    autocapitalize="characters" autofocus required />

                <x-input-error :messages="$errors->bookingDeletion->get('confirm')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-button.secondary x-on:click="$dispatch('close')">
                    @lang('Back')
                </x-button.secondary>

                <x-button.danger class="ml-3">
                    @lang('Permanently Delete')
                </x-button.danger>
            </div>
        </form>
    </x-modal>
@endcan
