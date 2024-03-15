@use('App\Enums\Accreditation')
@use('App\Enums\Role')
@use('App\Enums\Section')
<x-layout.app :header="$user->name" :title="__('Update - :name', $user->only('name'))">
    <section class="p-4 sm:px-8 max-w-xl">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Profile Information')
            </h2>
            <p class="mt-1 text-md text-gray-600 dark:text-gray-400">
                @lang("Update this User's profile information.")
            </p>
        </header>

        <form method="post" action="{{ route('user.update', $user) }}" class="mt-6 space-y-6" x-data="{
            submitted: false,
            user: {
                name: '{{ old('name', $user->name) }}',
                email: '{{ old('email', $user->email) }}',
                timezone: '{{ old('timezone', $user->timezone) }}',
                section: '{{ old('section', $user->section) }}',
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
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
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
                        @lang('This email address is unverified.')
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

            <div>
                <x-input-label for="section" :value="__('Section')" />
                <x-select-input id="section" name="section" class="mt-1 block" required x-model="user.section">
                    <x-select-input.enum :options="Section::class" lang="app.user.section.:value" />
                </x-select-input>
                <x-input-error class="mt-2" :messages="$errors->get('section')" />
            </div>

            <div>
                <x-input-label for="role" :value="__('Role')" />
                <x-select-input id="role" name="role" class="mt-1 block" required x-model="user.role">
                    @foreach (Role::cases() as $option)
                        <option value="{{ $option->value }}" @disabled(auth()->user()->role->compare($option) < 0)>
                            @lang('app.user.role.' . $option->value)
                        </option>
                    @endforeach
                </x-select-input>
            </div>

            @can('accredit', $user)
                <fieldset x-data="checkboxes({{ json_encode(Accreditation::cases()) }})" x-modelable="values" x-model="user.accreditations" class="space-y-1">
                    <legend class="font-bold after:content-[':'] text-gray-900 dark:text-gray-100">
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

            <footer class="flex items-center gap-4 mt-6">
                <x-button.primary x-bind:disabled="submitted"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Update') }}'" />

                <x-button.secondary :href="route('user.show', $user)">
                    @lang('Back')
                </x-button.secondary>
            </footer>
        </form>
    </section>
</x-layout.app>
