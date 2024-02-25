@use('App\Enums\Accreditation')
@use('App\Enums\Role')
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
            user: {
                name: '{{ old('name', $user->name) }}',
                email: '{{ old('email', $user->email) }}',
                timezone: '{{ old('timezone', $user->timezone) }}',
                role: '{{ old('role', $user->role) }}',
                accreditations: {{ old('accreditations', $user->accreditations) }},
            },
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
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus
                    autocomplete="off" x-model="user.name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required
                    autocomplete="off" x-model="user.email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                    <div class="text-sm mt-2 text-orange-800 dark:text-orange-200">
                        {{ __('This email address is unverified.') }}
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="timezone" :value="__('Timezone')" />
                <x-select-input id="timezone" name="timezone" class="mt-1 block" required x-model="user.timezone">
                    <x-select-input.timezones />
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
            </div>

            @can('manage', $user)
                <div>
                    <x-input-label for="role" :value="__('Role')" />
                    <x-select-input id="role" name="role" class="mt-1 block" required x-model="user.role">
                        <x-select-input.enum :options="Role::class" lang="app.user.role.:value" />
                    </x-select-input>
                </div>
            @endcan

            @can('manage', $user)
                <fieldset x-data="checkboxes({{ json_encode(Accreditation::cases()) }})" x-modelable="values" x-model="user.accreditations">
                    <legend class="text-xl font-medium">{{ __('Accreditations') }}</legend>

                    <label class="mt-1 block w-full">
                        <input type="checkbox" x-model="all" x-effect="$el.indeterminate = indeterminate()" />
                        {{ __('Select all') }}</label>

                    @foreach (Accreditation::cases() as $accreditation)
                        <label class="mt-1 block w-full">
                            <input type="checkbox" value="{{ $accreditation->value }}" name="accreditations[]"
                                x-model="values" />
                            {{ __("app.user.accreditation.$accreditation->value") }}</label>
                    @endforeach
                    <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                </fieldset>
            @endcan

            <div class="flex items-center gap-4">
                <x-button.primary>
                    {{ __('Update') }}
                </x-button.primary>

                <x-button.secondary :href="route('user.show', $user)">
                    {{ __('Back') }}
                </x-button.secondary>
            </div>
        </form>
    </section>
</x-layout.app>
