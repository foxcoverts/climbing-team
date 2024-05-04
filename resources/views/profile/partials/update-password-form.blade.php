<section class="max-w-xl">
    <header>
        <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6" x-data="{ submitted: false }"
        x-on:submit="setTimeout(() => submitted = true, 0)">
        @csrf
        @method('put')

        <div>
            <x-input-label for="current_password" :value="__('Current Password')" />
            <x-password-input id="current_password" name="current_password" class="mt-1 block w-full"
                autocomplete="current-password" required />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-password-input id="password" name="password" class="mt-1 block w-full" autocomplete="new-password"
                required />
            <x-password-rules :errors="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-password-input id="password_confirmation" name="password_confirmation" class="mt-1 block w-full"
                autocomplete="new-password" required />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-button.primary x-bind:disabled="submitted" :label="__('Save')"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'" />

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
