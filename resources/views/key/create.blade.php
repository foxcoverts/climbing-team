<x-layout.app :title="__('Add Key')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Add Key') }}
                </h1>
            </div>
        </header>

        <form action="{{ route('key.store') }}" method="post" x-data="{
            submitted: false,
            key: {{ Js::from([
                'name' => $key->name,
                'holder_id' => $key->holder_id,
            ]) }},
        }">
            @csrf
            <div class="p-4 sm:px-8 space-y-4">
                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-96 max-w-full"
                        maxlength="255" required autofocus x-model="key.name" />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="holder_id" :value="__('Holder')" />
                    <x-select-input id="holder_id" name="holder_id" class="mt-1 block" required x-model="key.holder_id">
                        <template x-if="!key.holder_id">
                            <option value="" selected></option>
                        </template>
                        <x-select-input.collection :options="$users" label_key="name" />
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('holder_id')" />
                </div>

                <footer class="flex items-start gap-4">
                    <x-button.primary :label="__('Save')" />
                    <x-button.secondary :href="route('key.index')" :label="__('Back')" />
                </footer>
            </div>
        </form>
    </section>
</x-layout.app>
