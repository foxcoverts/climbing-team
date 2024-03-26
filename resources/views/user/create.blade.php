@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('App\Enums\Section')
<x-layout.app :title="__('Add User')">
    <section class="p-4 sm:px-8 max-w-xl">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Add User')
            </h2>
        </header>

        <form method="post" action="{{ route('user.store') }}" class="mt-6 space-y-6" x-data="{
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
                    if (!this.user.timezone || this.user.timezone == 'UTC') {
                        this.user.timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;
                    }
                });
            },
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" maxlength="255"
                    required autofocus x-model="user.name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" maxlength="255"
                    required x-model="user.email" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />
            </div>

            <div>
                <x-input-label for="phone" :value="__('Phone')" />
                <x-text-input id="phone" name="phone" type="tel" class="mt-1 block w-40" x-model="user.phone"
                    x-mask:dynamic="$phone($input)" maxlength="15" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div>
                <x-fake-label :value="__('Password')" />
                <p class="mt-1 text-blue-800 dark:text-blue-200">
                    @lang('The user will be asked to set their own password.')
                </p>
            </div>

            <fieldset>
                <legend class="text-lg font-medium mb-1">@lang('Emergency Contact')</legend>
                <p class="mb-2 text-md text-blue-800 dark:text-blue-200">@lang('The lead instructor for a booking will be able to access these details should the need arise. If no details are provided then there may be a delay in contacting someone.')</p>
                <div class="flex flex-wrap gap-6">
                    <div class="grow shrink">
                        <x-input-label for="emergency_name" :value="__('Name')" />
                        <x-text-input id="emergency_name" name="emergency_name" type="tel"
                            class="mt-1 block w-full min-w-48" maxlength="100" x-bind:required="!!user.emergency_phone"
                            x-model="user.emergency_name" x-mask:dynamic="$phone($input)" maxlength="15" />
                        <x-input-error class="mt-2" :messages="$errors->get('emergency_name')" />
                    </div>
                    <div>
                        <x-input-label for="emergency_phone" :value="__('Phone')" />
                        <x-text-input id="emergency_phone" name="emergency_phone" class="mt-1 block w-40"
                            x-bind:required="!!user.emergency_name" x-model="user.emergency_phone" />
                        <x-input-error class="mt-2" :messages="$errors->get('emergency_phone')" />
                    </div>
                </div>
            </fieldset>

            <fieldset>
                <legend class="text-lg font-medium mb-2">@lang('Settings')</legend>
                <div class="space-y-6">
                    <div>
                        <x-input-label for="timezone" :value="__('Timezone')" />
                        <x-select-input id="timezone" name="timezone" class="mt-1 block" required
                            x-model="user.timezone">
                            <x-select-input.timezones />
                        </x-select-input>
                        <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
                    </div>

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
                        <x-select-input id="role" name="role" class="mt-1 block" required x-model="user.role">
                            @foreach (Role::cases() as $option)
                                <option value="{{ $option->value }}" @selected($option == Role::Guest)
                                    @disabled(auth()->user()->role->compare($option) < 0)>
                                    @lang('app.user.role.' . $option->value)
                                </option>
                            @endforeach
                        </x-select-input>
                    </div>

                    @can('accredit', $user)
                        <fieldset x-data="checkboxes({{ Js::from(Accreditation::cases()) }})" x-modelable="values" x-model="user.accreditations"
                            class="space-y-1">
                            <legend class="font-bold text-gray-900 dark:text-gray-100">
                                @lang('Accreditations')</legend>

                            <label class="flex w-full items-center gap-1">
                                <input type="checkbox" name="all" @change="selectAll" x-effect="indeterminate($el)" />
                                <span>@lang('Select all')</span>
                            </label>

                            @foreach (Accreditation::cases() as $accreditation)
                                <label class="flex w-full items-center gap-1">
                                    <input type="checkbox" value="{{ $accreditation->value }}" name="accreditations[]"
                                        x-model="values" />
                                    <span>@lang("app.user.accreditation.$accreditation->value")</span>
                                </label>
                            @endforeach
                            <x-input-error class="mt-2" :messages="$errors->get('user_id')" />
                        </fieldset>
                    @endcan
                </div>
            </fieldset>

            <div class="flex items-center gap-4">
                <x-button.primary x-bind:disabled="submitted"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Create') }}'" />

                @can('viewAny', App\Models\User::class)
                    <x-button.secondary :href="route('user.index')">
                        @lang('Back')
                    </x-button.secondary>
                @endcan
            </div>
        </form>
    </section>
</x-layout.app>
