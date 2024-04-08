<x-layout.guest :title="__('Reset Password')">
    <form method="POST" action="{{ route('password.store') }}" x-data="{ submitted: false }"
        x-on:submit="setTimeout(() => submitted = true, 0)">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)"
                required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-password-input id="password" name="password" class="block mt-1 w-full" required
                autocomplete="new-password" />
            <x-password-rules :errors="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-password-input id="password_confirmation" name="password_confirmation" class="block mt-1 w-full" required
                autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button.primary x-bind:disabled="submitted" :label="__('Reset Password')"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Reset Password') }}'" />
        </div>
    </form>
</x-layout.guest>
