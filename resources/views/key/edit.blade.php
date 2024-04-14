<x-layout.app :title="__('Update: :name', ['name' => $key->name])">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ $key->name }}
                </h1>
            </div>
        </header>

        @if ($ajax)
            <form x-target id="{{ sprintf('key-%s-name', $key->id) }}" method="POST"
                action="{{ route('key.update', $key) }}">
                @csrf @method('PATCH')
                <input type="text" name="name" value="{{ $key->name }}" autocomplete="off" data-1p-ignore
                    class="text-lg font-medium max-w-full border-0 p-0 text-left -ml-1 pl-1
                    disabled:cursor-not-allowed disabled:text-gray-400 dark:bg-gray-900 dark:text-gray-300 dark:disabled:text-gray-600
                    focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600"
                    style="color-scheme: light dark;" x-autofocus
                    @keyup.escape="$el.form.reset(); $el.form.requestSubmit();"
                    @click.outside="$el.form.requestSubmit()" />
                <input type="submit" :class="'hidden'" />
            </form>
        @else
            <form method="POST" action="{{ route('key.update', $key) }}" aria-label="__('Update Key')"
                x-data="{
                    key: {{ Js::from([
                        'name' => $key->name,
                    ]) }},
                }">
                @csrf @method('PATCH')

                <div class="p-4 sm:px-8 space-y-4">
                    <div>
                        <x-input-label for="name" :value="__('Key Name')" />
                        <x-text-input id="name" name="name" class="mt-1 block" required autofocus
                            x-model="key.name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <footer class="flex items-start gap-4">
                        <x-button.primary :label="__('Save')" />
                        <x-button.secondary :href="route('key.index')" :label="__('Back')" />
                    </footer>
                </div>
            </form>
        @endif
    </section>
</x-layout.app>
