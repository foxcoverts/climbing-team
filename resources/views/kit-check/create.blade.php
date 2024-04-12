@use('Carbon\Carbon')
<x-layout.app :title="__('Record Kit Check')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10 px-4 sm:px-8">
            <div class="flex items-center justify-between max-w-prose">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">
                    @lang('Record Kit Check')
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('kit-check.store') }}" class="p-4 sm:px-8" x-data="{
            submitted: false,
            kitCheck: {{ Js::from([
                'checked_by_id' => old('checked_by_id', $kitCheck->checked_by_id),
                'checked_on' => old('checked_on', $kitCheck->checked_on->format('Y-m-d')),
                'comment' => old('comment', $kitCheck->comment),
                'user_ids' => old('user_ids', $user_ids),
            ]) }},
            checkUserIds(value) {
                el = document
                    .getElementById('user_ids')
                    .getElementsByTagName('input').item(0);
                if (value.length > 0) {
                    el.setCustomValidity('');
                } else {
                    el.setCustomValidity('You must select at least one user.');
                }
            },
            init() {
                this.$watch('kitCheck.user_ids', this.checkUserIds);
                this.checkUserIds(this.kitCheck.user_ids);
            },
        }"
            x-on:submit="setTimeout(() => submitted = true, 0)">
            @csrf

            <div class="space-y-6 max-w-prose">
                <div>
                    <x-input-label for="checked_on" :value="__('Checked On')" />
                    <x-text-input id="checked_on" name="checked_on" type="date" class="mt-1" :max="Carbon::now()->format('Y-m-d')"
                        required x-model="kitCheck.checked_on" />
                    <x-input-error class="mt-2" :messages="$errors->get('checked_on')" />
                </div>

                <div>
                    <x-input-label for="checked_by_id" :value="__('Checked By')" />
                    <x-select-input id="checked_by_id" name="checked_by_id" class="mt-1" required
                        x-model="kitCheck.checked_by_id">
                        <x-select-input.collection :options="$checkers" label_key="name" />
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('checked_by_id')" />
                </div>

                <div>
                    <x-input-label for="comment" :value="__('Comment')" />
                    <x-textarea id="comment" name="comment" class="mt-1 w-full" x-model="kitCheck.comment" />
                    <p class="text-sm">@lang("This comment will be copied on to each user's kit check entry.")</p>
                    <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                </div>

                <fieldset id="user_ids">
                    <legend class="font-bold text-gray-900 dark:text-gray-100">@lang('Users')</legend>

                    @foreach ($users as $user)
                        <label class="flex w-full items-center gap-1">
                            <input type="checkbox" value="{{ $user->id }}" name="user_ids[]"
                                x-model="kitCheck.user_ids" />
                            <span>{{ $user->name }}</span>
                        </label>
                    @endforeach
                    <x-input-error class="mt-2" :messages="$errors->get('user_ids')" />
                </fieldset>
            </div>

            <footer class="mt-6 flex flex-wrap items-center gap-4">
                <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Save')"
                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'" />

                @can('viewAny', App\Models\KitCheck::class)
                    <x-button.secondary :href="route('kit-check.index')">
                        @lang('Back')
                    </x-button.secondary>
                @endcan
            </footer>
        </form>
    </section>
</x-layout.app>
