<x-layout.app :title="__('Transfer Key')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ $key->name }}
                </h1>
            </div>
        </header>

        <form {!! $ajax ? 'x-target="transfer-key keys"' : '' !!} id="transfer-key" method="POST" action="{{ route('key.transfer', $key) }}"
            aria-label="__('Transfer Key')" x-data="{
                key: {{ Js::from([
                    'name' => $key->name,
                    'holder_id' => $key->holder_id,
                ]) }},
            }">
            @csrf
            @method('PATCH')
            <div class="p-4 sm:px-8 space-y-4">
                <div>
                    <x-input-label for="holder_id" :value="__('Transfer key to')" />
                    <x-select-input id="holder_id" name="holder_id" class="mt-1 block" required autofocus
                        x-model="key.holder_id">
                        <option value="{{ $key->holder_id }}" selected>{{ $key->holder->name }}</option>
                        <hr />
                        <x-select-input.collection :options="$users" label_key="name" :except="$key->holder_id" />
                    </x-select-input>
                    <x-input-error class="mt-2" :messages="$errors->get('holder_id')" />
                </div>

                <footer class="flex items-start gap-4">
                    <x-button.primary :label="__('Transfer')" />

                    @unless ($ajax)
                        <x-button.secondary :href="route('key.index')" :label="__('Back')" />
                    @endunless
                </footer>
            </div>
        </form>
    </section>
</x-layout.app>
