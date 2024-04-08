<x-layout.guest :title="__('Account Setup')">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        @lang('Something is wrong with your link. Please confirm your email address and we will email you a new account setup link.')
    </div>

    <form method="POST" action="{{ route('setup-account-link', $user) }}" x-data="{ submitted: false }"
        x-on:submit="setTimeout(() => submitted = true, 0)">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button.primary x-bind:disabled="submitted" :label="__('Email Account Setup Link')"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Email Account Setup Link') }}'" />
        </div>
    </form>
</x-layout.guest>
