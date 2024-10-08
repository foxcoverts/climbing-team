@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('App\Enums\Section')
<x-layout.app :header="$user->name" :title="__('Update - :name', $user->only('name'))">
    <section x-data="{
        submitted: false,
        user: {{ Js::from([
            'name' => old('name', $user->name),
            'email' => old('email', $user->email),
            'phone' => old('phone', $user->phone?->formatForCountry('GB')),
            'emergency_name' => old('emergency_name', $user->emergency_name),
            'emergency_phone' => old('emergency_phone', $user->emergency_phone?->formatForCountry('GB')),
            'timezone' => old('timezone', $user->timezone?->getName()),
            'section' => old('section', $user->section),
            'role' => old('role', $user->role),
            'accreditations' => old('accreditations', $user->accreditations->pluck('value')->all()),
        ]) }},
        init() {
            $nextTick(() => {
                if (!this.user.timezone) {
                    this.user.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                }
            });
        },
    }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Profile Information') }}
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('user.update', $user) }}" id="update-user"
            x-on:submit="setTimeout(() => submitted = true, 0)" class="p-4 sm:px-8">
            @method('PATCH')
            @csrf

            <div class="max-w-prose">
                <p class="mb-6 text-md text-gray-600 dark:text-gray-400">
                    {{ __("Update this User's profile information.") }}
                </p>

                <div class="mb-6">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required
                        autofocus autocomplete="off" x-model="user.name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <fieldset x-data="{ open: false }">
                    <legend class="text-lg sm:text-xl font-medium my-2 flex items-center space-x-1">
                        <button @click="open = !open" type="button" x-bind:aria-pressed="open"
                            class="flex items-center space-x-1">
                            <x-icon.cheveron.down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>{{ __('Contact Details') }}</span>
                        </button>
                    </legend>

                    <div class="space-y-6 mb-6" x-cloak x-show="open" x-transition>
                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full"
                                required autocomplete="off" x-model="user.email" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />

                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                <div class="text-sm mt-2 text-orange-800 dark:text-orange-200">
                                    {{ __('This email address is unverified.') }}
                                </div>
                            @endif
                        </div>

                        <div>
                            <x-input-label for="phone" :value="__('Phone')" />
                            <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-40"
                                x-model="user.phone" x-mask:dynamic="$phone($input)" maxlength="15" />
                            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                        </div>
                    </div>
                </fieldset>

                <fieldset x-data="{ open: false }">
                    <legend class="text-lg sm:text-xl font-medium my-2 flex items-center space-x-1">
                        <button @click="open = !open" type="button" x-bind:aria-pressed="open"
                            class="flex items-center space-x-1">
                            <x-icon.cheveron.down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>{{ __('Emergency Contact') }}</span>
                        </button>
                    </legend>

                    <p class="text-md mb-2 text-blue-800 dark:text-blue-200" x-cloak x-show="open" x-transition>
                        {{ __('The lead instructor for a booking will be able to access these details should the need arise. If no details are provided then there may be a delay in contacting someone.') }}
                    </p>

                    <div class="flex flex-wrap gap-6 mb-6" x-cloak x-show="open" x-transition>
                        <div class="grow shrink">
                            <x-input-label for="emergency_name" :value="__('Name')" />
                            <x-text-input id="emergency_name" name="emergency_name" class="mt-1 block w-full min-w-48"
                                maxlength="100" x-bind:required="!!user.emergency_phone"
                                x-model="user.emergency_name" />
                            <x-input-error class="mt-2" :messages="$errors->get('emergency_name')" />
                        </div>
                        <div>
                            <x-input-label for="emergency_phone" :value="__('Phone')" />
                            <x-text-input id="emergency_phone" name="emergency_phone" class="mt-1 block w-40"
                                x-bind:required="!!user.emergency_name" x-model="user.emergency_phone"
                                x-mask:dynamic="$phone($input)" maxlength="15" />
                            <x-input-error class="mt-2" :messages="$errors->get('emergency_phone')" />
                        </div>
                    </div>
                </fieldset>

                <fieldset x-data="{ open: true }">
                    <legend class="text-lg sm:text-xl font-medium my-2 flex items-center space-x-1">
                        <button @click="open = !open" type="button" x-bind:aria-pressed="open"
                            class="flex items-center space-x-1">
                            <x-icon.cheveron.down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>{{ __('Settings') }}</span>
                        </button>
                    </legend>

                    <div class="space-y-6 mb-6" x-show="open" x-transition>
                        @can('manage', $user)
                            <div>
                                <x-input-label for="section" :value="__('Section')" />
                                <x-select-input id="section" name="section" class="mt-1 block" required
                                    x-model="user.section">
                                    <x-select-input.enum :options="Section::class" lang="app.user.section.:value" />
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('section')" />
                            </div>

                            <div>
                                <x-input-label for="role" :value="__('Role')" />
                                <x-select-input id="role" name="role" class="mt-1 block" required
                                    x-model="user.role">
                                    @foreach (Role::cases() as $option)
                                        <option value="{{ $option->value }}" @disabled(auth()->user()->role->compare($option) < 0)>
                                            {{ __('app.user.role.' . $option->value) }}
                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>
                        @endcan

                        @can('accredit', $user)
                            <fieldset x-data="checkboxes({{ Js::from(Accreditation::cases()) }})" x-modelable="values" x-model="user.accreditations"
                                class="space-y-2">
                                <legend class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ __('Accreditations') }}</legend>

                                <label class="block">
                                    <x-input-checkbox name="all" @change="selectAll" x-effect="indeterminate($el)" />
                                    {{ __('Select all') }}
                                </label>

                                @foreach (Accreditation::cases() as $accreditation)
                                    <label class="block">
                                        <x-input-checkbox value="{{ $accreditation->value }}" name="accreditations[]"
                                            x-model="values" />
                                        {{ __("app.user.accreditation.$accreditation->value") }}
                                    </label>
                                @endforeach
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </fieldset>
                        @endcan

                        <div>
                            <x-input-label for="timezone" :value="__('Timezone')" />
                            <x-select-input id="timezone" name="timezone" required x-model="user.timezone"
                                class="mt-1 w-full overflow-ellipsis">
                                <x-select-input.timezones />
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>

        <footer class="px-4 pb-4 sm:px-8 flex flex-wrap items-center gap-4">
            <x-button.primary class="whitespace-nowrap" form="update-user" x-bind:disabled="submitted"
                :label="__('Update')" x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Update') }}'" />
            @include('user.partials.delete-button')
            <x-button.secondary :href="route('user.show', $user)" :label="__('Back')" />
        </footer>
    </section>
</x-layout.app>
