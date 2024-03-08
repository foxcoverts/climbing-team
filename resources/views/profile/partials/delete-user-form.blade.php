<section class="space-y-6 max-w-xl">
    <header>
        <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
            @lang('Delete Account')
        </h2>

        <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
            @lang('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')
        </p>
    </header>

    <x-button.danger x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">@lang('Delete Account')</x-button.danger>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                @lang('Are you sure you want to delete your account?')
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                @lang('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="@lang('Password')" class="sr-only" />

                <x-text-input id="password" name="password" type="password" class="mt-1 block w-3/4"
                    placeholder="@lang('Password')" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <x-button.secondary x-on:click="$dispatch('close')">
                    @lang('Back')
                </x-button.secondary>

                <x-button.danger class="ml-3">
                    @lang('Delete Account')
                </x-button.danger>
            </div>
        </form>
    </x-modal>
</section>
