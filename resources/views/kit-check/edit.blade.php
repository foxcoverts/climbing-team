@use('Carbon\Carbon')
<x-layout.app :title="__(':Name - Edit Kit Check', ['name' => $kitCheck->user->name])">
    <section x-data="{ submitted: false }">
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10 px-4 sm:px-8">
            <div class="flex items-center justify-between max-w-prose">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">
                    @lang(':Name - Edit Kit Check', ['name' => $kitCheck->user->name])
                </h1>
            </div>
        </header>

        <form method="post" action="{{ route('kit-check.update', $kitCheck) }}" id="update-kit-check" class="p-4 sm:px-8"
            x-data="{
                kitCheck: {{ Js::from([
                    'checked_by_id' => old('checked_by_id', $kitCheck->checked_by_id),
                    'checked_on' => old('checked_on', $kitCheck->checked_on->format('Y-m-d')),
                    'comment' => old('comment', $kitCheck->comment),
                ]) }},
            }" x-on:submit="setTimeout(() => submitted = 'update-kit-check', 0)">
            @csrf @method('PATCH')

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
                    <x-input-error class="mt-2" :messages="$errors->get('comment')" />
                </div>
            </div>
        </form>

        @can('delete', $kitCheck)
            <form method="POST" action="{{ route('kit-check.destroy', $kitCheck) }}" id="delete-kit-check"
                x-on:submit="setTimeout(() => submitted = 'delete-kit-check', 0)">
                @csrf @method('DELETE')
            </form>
        @endcan

        <footer class="p-4 sm:px-8 flex flex-wrap items-center gap-4">
            <x-button.primary class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Save')"
                x-text="submitted == 'update-kit-check' ? '{{ __('Please wait...') }}' : '{{ __('Save') }}'"
                form="update-kit-check" />

            @can('delete', $kitCheck)
                <x-button.danger class="whitespace-nowrap" x-bind:disabled="submitted" :label="__('Delete')"
                    x-text="submitted == 'delete-kit-check' ? '{{ __('Please wait...') }}' : '{{ __('Delete') }}'"
                    form="delete-kit-check" />
            @endcan

            @can('view', $kitCheck)
                <x-button.secondary :href="route('kit-check.user.index', $kitCheck->user)">
                    @lang('Back')
                </x-button.secondary>
            @endcan
        </footer>
    </section>
</x-layout.app>
