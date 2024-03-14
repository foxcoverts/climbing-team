<x-layout.guest :title="__('Forgotten Password')">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        @lang('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" x-data="{ submitted: false }"
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
            <x-button.primary x-bind:disabled="submitted"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Email Password Reset Link') }}'" />
        </div>
    </form>
</x-layout.guest>
