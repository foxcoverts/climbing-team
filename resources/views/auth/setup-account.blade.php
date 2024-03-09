<x-layout.guest :title="__('Account Setup')">
    <form method="POST" action="{{ url()->full() }}" x-data="{
        user: {},
        originalEmail: '{{ $user->email }}',
        init() {
            $nextTick(() => {
                if (!this.user.timezone || this.user.timezone == 'UTC') {
                    this.user.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                }
            });
        },
    }">
        @csrf

        <div>
            <p>@lang('Please confirm your details and set a new password.')</p>
        </div>

        <!-- Name -->
        <div class="mt-4">
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required
                autofocus autocomplete="name" x-model.fill="user.name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)"
                required autocomplete="username" x-model.fill="user.email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <p class="text-sm mt-2 text-blue-600 dark:text-blue-400" x-cloak x-show="user.email != originalEmail">
                @lang('You will need to verify your new email address.')
            </p>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-password-input id="password" name="password" class="block mt-1 w-full" required
                autocomplete="new-password" x-model.fill="user.password" />

            <x-password-rules :errors="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-password-input id="password_confirmation" name="password_confirmation" class="block mt-1 w-full" required
                autocomplete="new-password" x-model.fill="user.password_confirmation" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Timezone -->
        <div class="mt-4 hidden">
            <x-input-label for="timezone" :value="__('Timezone')" />
            <x-select-input id="timezone" name="timezone" class="mt-1 block" required :value="old('timezone', $user->timezone)"
                x-model.fill="user.timezone">
                <x-select-input.timezones />
            </x-select-input>
            <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-button.primary class="ml-4">
                @lang('Setup')
            </x-button.primary>
        </div>
    </form>
</x-layout.guest>
