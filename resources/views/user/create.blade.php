@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('App\Enums\Section')
<x-layout.app :title="__('Add User')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8 sm:z-10">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Add User') }}
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('user.store') }}" class="p-4 sm:px-8" x-data="{
            submitted: false,
            user: {{ Js::from([
                'name' => old('name', $user->name),
                'email' => old('email', $user->email),
                'timezone' => old('timezone', $user->timezone?->getName()),
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
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf

            <div class="space-y-6 max-w-prose">
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
                    <x-fake-label :value="__('Password')" />
                    <p class="mt-1 text-blue-800 dark:text-blue-200">
                        {{ __('The user will be asked to set their own password.') }}
                    </p>
                </div>

                <fieldset>
                    <legend class="text-lg font-medium mb-2">{{ __('Settings') }}</legend>
                    <div class="space-y-6">
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
                                    <option value="{{ $option->value }}" @selected($option == Role::Guest)
                                        @disabled(auth()->user()->role->compare($option) < 0)>
                                        {{ __('app.user.role.' . $option->value) }}
                                    </option>
                                @endforeach
                            </x-select-input>
                        </div>

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
                                <x-input-error class="mt-2" :messages="$errors->get('accreditations')" />
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

            <footer class="mt-6 flex flex-wrap items-center gap-4">
                <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Create')"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Create') }}'" />

                @can('viewAny', App\Models\User::class)
                    <x-button.secondary :href="route('user.index')" :label="__('Back')" />
                @endcan
            </footer>
        </form>
    </section>
</x-layout.app>
