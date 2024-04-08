<section class="max-w-xl">
    <header>
        <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
            @lang('Profile Information')
        </h2>

        <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
            @lang("Update your account's profile information and email address.")
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" x-data="{
        submitted: false,
        user: {{ Js::from([
            'name' => old('name', $user->name),
            'email' => old('email', $user->email),
            'phone' => old('phone', $user->phone?->formatForCountry('GB')),
            'emergency_name' => old('emergency_name', $user->emergency_name),
            'emergency_phone' => old('emergency_phone', $user->emergency_phone?->formatForCountry('GB')),
            'timezone' => old('timezone', (string) $user->timezone),
        ]) }},
        init() {
            $nextTick(() => {
                if (!this.user.timezone) {
                    this.user.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                }
            });
        },
    }"
        x-on:submit="setTimeout(() => submitted = true, 0)">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus
                autocomplete="name" x-model="user.name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required
                autocomplete="username" x-model="user.email" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                        @lang('Your email address is unverified.')

                        <button form="send-verification"
                            class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                            @lang('Click here to re-send the verification email.')
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                            @lang('A new verification link has been sent to your email address.')
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="phone" :value="__('Phone')" />
            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-40" autocomplete="tel"
                x-model="user.phone" x-mask:dynamic="$phone($input)" maxlength="15" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <fieldset>
            <legend class="text-lg font-medium mb-1">@lang('Emergency Contact')</legend>
            <p class="mb-2 text-md text-blue-800 dark:text-blue-200">@lang('The lead instructor for a booking will be able to access these details should the need arise. If no details are provided then there may be a delay in contacting someone.')</p>
            <div class="flex flex-wrap gap-6">
                <div class="grow shrink">
                    <x-input-label for="emergency_name" :value="__('Name')" />
                    <x-text-input id="emergency_name" name="emergency_name" class="mt-1 block w-full min-w-48"
                        maxlength="100" x-model="user.emergency_name" x-bind:required="!!user.emergency_phone" />
                    <x-input-error class="mt-2" :messages="$errors->get('emergency_name')" />
                </div>
                <div>
                    <x-input-label for="emergency_phone" :value="__('Phone')" />
                    <x-text-input id="emergency_phone" name="emergency_phone" type="tel" class="mt-1 block w-40"
                        x-model="user.emergency_phone" x-bind:required="!!user.emergency_name"
                        x-mask:dynamic="$phone($input)" maxlength="15" />
                    <x-input-error class="mt-2" :messages="$errors->get('emergency_phone')" />
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend class="text-lg font-medium mb-2">@lang('Settings')</legend>
            <div>
                <x-input-label for="timezone" :value="__('Timezone')" />
                <x-select-input id="timezone" name="timezone" class="mt-1 block w-full overflow-ellipsis" required
                    x-model="user.timezone">
                    <x-select-input.timezones />
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
            </div>
        </fieldset>

        <div class="flex items-center gap-4">
            <x-button.primary x-bind:disabled="submitted" :label="__('Save')"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'" />

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400">@lang('Saved.')</p>
            @endif
        </div>
    </form>
</section>
