@can('delete', $user)
    <x-button.danger x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        :label="__('Delete')" />

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('user.destroy', $user) }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete this account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once this account is deleted, all of its resources and data will be permanently deleted.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="confirm">
                    {{ __('Please type "DELETE" to confirm you would like to permanently delete this account.') }}
                </x-input-label>

                <x-text-input id="confirm" name="confirm" type="text" class="mt-1 block w-3/4" :value="old('confirm')"
                    autocapitalize="characters" autofocus required />

                <x-input-error :messages="$errors->userDeletion->get('confirm')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-button.secondary x-on:click="$dispatch('close')" :label="__('Back')" />

                <x-button.danger class="ml-3" :label="__('Permanently Delete')" />
            </div>
        </form>
    </x-modal>
@endcan
