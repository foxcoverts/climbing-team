@use('App\Enums\CommentNotificationOption')
<x-layout.app :title="__('Notification Settings')">
    <section x-data="{
        submitted: false,
        settings: {{ Js::from([
            'comment_mail' => old('comment_mail', $settings->comment_mail),
            'invite_mail' => old('invite_mail', $settings->invite_mail),
            'change_mail' => old('change_mail', $settings->change_mail),
            'confirm_mail' => old('confirm_mail', $settings->confirm_mail),
            'cancel_mail' => old('cancel_mail', $settings->cancel_mail),
        ]) }},
    }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.notifications style="height: .75lh" class="fill-current" aria-hidden="true" />
                    <span>{{ __('Notification Settings') }}</span>
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('profile.notifications.update') }}" id="update-settings"
            x-on:submit="setTimeout(() => submitted = true, 0)" class="p-4 sm:px-8">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div class="space-y-1">
                    <x-fake-label :value="__('Invites')" />
                    <p class="text-sm">
                        {{ __('Get an e-mail when you are invited to something.') }}
                    </p>
                    <x-select-input id="invite_mail" name="invite_mail" class="mt-1 block">
                        <option value="" @selected(!is_bool($settings->invite_mail))>
                            {{ __('Default') }} ({{ $settings::default('invite_mail') ? __('On') : __('Off') }})
                        </option>
                        <hr>
                        <option value="1" @selected($settings->invite_mail === true)>{{ __('On') }}</option>
                        <option value="0" @selected($settings->invite_mail === false)>{{ __('Off') }}</option>
                    </x-select-input>
                    <x-input-error :messages="$errors->get('status')" />
                </div>

                <div class="space-y-1">
                    <x-fake-label :value="__('Changes')" />
                    <p class="text-sm">
                        {{ __('Get an e-mail when something is changed.') }}
                    </p>
                    <x-select-input id="change_mail" name="change_mail" class="mt-1 block">
                        <option value="" @selected(!is_bool($settings->change_mail))>
                            {{ __('Default') }} ({{ $settings::default('change_mail') ? __('On') : __('Off') }})
                        </option>
                        <hr>
                        <option value="1" @selected($settings->change_mail === true)>{{ __('On') }}</option>
                        <option value="0" @selected($settings->change_mail === false)>{{ __('Off') }}</option>
                    </x-select-input>
                    <x-input-error :messages="$errors->get('status')" />
                </div>

                <div class="space-y-1">
                    <x-fake-label :value="__('Confirmed')" />
                    <p class="text-sm">
                        {{ __('Get an e-mail when something is confirmed.') }}
                    </p>
                    <x-select-input id="confirm_mail" name="confirm_mail" class="mt-1 block">
                        <option value="" @selected(!is_bool($settings->confirm_mail))>
                            {{ __('Default') }} ({{ $settings::default('confirm_mail') ? __('On') : __('Off') }})
                        </option>
                        <hr>
                        <option value="1" @selected($settings->confirm_mail === true)>{{ __('On') }}</option>
                        <option value="0" @selected($settings->confirm_mail === false)>{{ __('Off') }}</option>

                    </x-select-input>
                    <x-input-error :messages="$errors->get('status')" />
                </div>

                <div class="space-y-1">
                    <x-fake-label :value="__('Cancelled')" />
                    <p class="text-sm">
                        {{ __('Get an e-mail when something is cancelled.') }}
                    </p>
                    <x-select-input id="cancel_mail" name="cancel_mail" class="mt-1 block">
                        <option value="" @selected(!is_bool($settings->cancel_mail))>
                            {{ __('Default') }} ({{ $settings::default('cancel_mail') ? __('On') : __('Off') }})
                        </option>
                        <hr>
                        <option value="1" @selected($settings->cancel_mail === true)>{{ __('On') }}</option>
                        <option value="0" @selected($settings->cancel_mail === false)>{{ __('Off') }}</option>

                    </x-select-input>
                    <x-input-error :messages="$errors->get('status')" />
                </div>

                <div class="space-y-1">
                    <x-fake-label :value="__('Comments')" />
                    <p class="text-sm">
                        {{ __('Get an e-mail when a comment is made.') }}
                    </p>
                    <x-select-input id="comment_mail" name="comment_mail" class="mt-1 block"
                        x-model.fill="settings.comment_mail">
                        <option value="" selected>
                            {{ __('Default') }}
                            ({{ __('app.notification.comment-option.' . $settings::default('comment_mail')->value) }})
                        </option>
                        <hr>
                        <x-select-input.enum :options="CommentNotificationOption::class" lang="app.notification.comment-option.:value" />
                    </x-select-input>
                    <x-input-error :messages="$errors->get('status')" />
                </div>
            </div>
        </form>

        <form method="post" action="{{ route('profile.notifications.destroy') }}" id="delete-settings">
            @csrf
            @method('delete')
        </form>

        <footer class="flex flex-wrap items-start gap-4 mt-2 mb-4 p-4 sm:px-8">
            <x-button.primary form="update-settings" class="whitespace-nowrap" x-bind:disabled="submitted"
                :label="__('Save')" x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'" />

            @if ($settings->exists)
                <x-button.danger form="delete-settings" class="whitespace-nowrap" x-bind:disabled="submitted"
                    :label="__('Clear')" x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Clear') }}'" />
            @endif

            <x-button.secondary :href="route('profile.edit')" :label="__('Back')" />
        </footer>
    </section>
</x-layout.app>
