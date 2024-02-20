<x-layout.app :header="$user->name" :title="__('Update - :name', $user->only('name'))">
    <section class="p-4 sm:p-8 max-w-xl">
        <header>
            <h2 class="text-3xl font-medium text-gray-900 dark:text-gray-100">
                {{ __('Profile Information') }}
            </h2>
            <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
                {{ __("Update this User's profile information.") }}
            </p>
        </header>

        <form method="post" action="{{ route('user.update', $user) }}" class="mt-6 space-y-6" x-data="{
            user: {},
            init() {
                $nextTick(() => {
                    if (!this.user.timezone) {
                        this.user.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    }
                });
            },
        }">
            @method('PATCH')
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)"
                    required autofocus x-model.fill="user.name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)"
                    required x-model.fill="user.email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="text-sm mt-2 text-orange-800 dark:text-orange-200">
                        {{ __('This email address is unverified.') }}
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="timezone" :value="__('Timezone')" />
                <x-select-input id="timezone" name="timezone" class="mt-1 block" required :value="old('timezone', $user->timezone)"
                    x-model.fill="user.timezone">
                    <x-select-input.timezones />
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
            </div>

            <div class="flex items-center gap-4">
                <x-button.primary>
                    {{ __('Update') }}
                </x-button.primary>

                <x-button.secondary :href="route('user.show', $user)">
                    {{ __('Cancel') }}
                </x-button.secondary>
            </div>
        </form>
    </section>
</x-layout.app>
