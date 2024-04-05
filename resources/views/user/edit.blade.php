@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('App\Enums\Section')
<x-layout.app :header="$user->name" :title="__('Update - :name', $user->only('name'))">
    <section class="p-4 sm:px-8 max-w-xl" x-data="{
        submitted: false,
        user: {{ Js::from([
            'name' => old('name', $user->name),
            'email' => old('email', $user->email),
            'phone' => old('phone', $user->phone?->formatForCountry('GB')),
            'emergency_name' => old('emergency_name', $user->emergency_name),
            'emergency_phone' => old('emergency_phone', $user->emergency_phone?->formatForCountry('GB')),
            'timezone' => old('timezone', (string) $user->timezone),
            'section' => old('section', $user->section),
            'role' => old('role', $user->role),
            'accreditations' => old('accreditations', $user->accreditations->all()),
        ]) }},
        init() {
            $nextTick(() => {
                if (!this.user.timezone) {
                    this.user.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                }
            });
        },
    }">
        <header>
            <h2 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Profile Information')
            </h2>
            <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
                @lang("Update this User's profile information.")
            </p>
        </header>

        <form method="post" action="{{ route('user.update', $user) }}" id="update-user"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @method('PATCH')
            @csrf

            <div class="mt-6">
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
                            <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>@lang('Contact Details')</span>
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
                                    @lang('This email address is unverified.')
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
                            <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>@lang('Emergency Contact')</span>
                        </button>
                    </legend>

                    <p class="text-md mb-2 text-blue-800 dark:text-blue-200" x-cloak x-show="open" x-transition>
                        @lang('The lead instructor for a booking will be able to access these details should the need arise. If no details are provided then there may be a delay in contacting someone.')</p>

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
                            <x-icon.cheveron-down aria-hidden="true" class="w-4 h-4 fill-current transition-transform"
                                ::class="open ? '' : '-rotate-90'" />
                            <span>@lang('Settings')</span>
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
                                            @lang('app.user.role.' . $option->value)
                                        </option>
                                    @endforeach
                                </x-select-input>
                            </div>
                        @endcan

                        @can('accredit', $user)
                            <fieldset x-data="checkboxes({{ Js::from(Accreditation::cases()) }})" x-modelable="values" x-model="user.accreditations"
                                class="space-y-1">
                                <legend class="font-bold text-gray-900 dark:text-gray-100">
                                    @lang('Accreditations')</legend>

                                <label class="flex w-full items-center gap-1">
                                    <input type="checkbox" name="all" @change="selectAll"
                                        x-effect="indeterminate($el)" />
                                    <span>@lang('Select all')</span>
                                </label>

                                @foreach (Accreditation::cases() as $accreditation)
                                    <label class="flex w-full items-center gap-1">
                                        <input type="checkbox" value="{{ $accreditation->value }}"
                                            name="accreditations[]" x-model="values" />
                                        <span>@lang("app.user.accreditation.$accreditation->value")</span>
                                    </label>
                                @endforeach
                                <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                            </fieldset>
                        @endcan

                        <div>
                            <x-input-label for="timezone" :value="__('Timezone')" />
                            <x-select-input id="timezone" name="timezone" class="mt-1 block" required
                                x-model="user.timezone">
                                <x-select-input.timezones />
                            </x-select-input>
                            <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
                        </div>
                    </div>
                </fieldset>
            </div>
        </form>

        <footer class="flex flex-wrap items-center gap-4 mt-6">
            <x-button.primary x-bind:disabled="submitted" form="update-user" class="whitespace-nowrap"
                x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Update') }}'" />
            @include('user.partials.delete-button')
            <x-button.secondary :href="route('user.show', $user)">
                @lang('Back')
            </x-button.secondary>
        </footer>
    </section>
</x-layout.app>
