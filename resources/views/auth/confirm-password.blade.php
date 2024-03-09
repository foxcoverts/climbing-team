<x-layout.guest :title="__('Confirm Password')">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        @lang('This is a secure area of the application. Please confirm your password before continuing.')
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-password-input id="password" name="password" class="block mt-1 w-full" required
                autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-button.primary>
                @lang('Confirm')
            </x-button.primary>
        </div>
    </form>
</x-layout.guest>
