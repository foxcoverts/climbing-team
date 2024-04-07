<x-layout.app :title="__('Update: :name', ['name' => $key->name])">
    <section>
        <header class="p-4 sm:px-8 bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10">
            <h1 class="text-2xl font-medium">{{ $key->name }}</h1>
        </header>

        @if ($ajax)
            <form x-target id="{{ sprintf('key-%s-name', $key->id) }}" method="POST"
                action="{{ route('key.update', $key) }}">
                @csrf @method('PATCH')
                <input type="text" name="name" value="{{ $key->name }}"
                    class="text-lg font-medium max-w-full border-0 p-0 text-left -ml-1 pl-1" autocomplete="off"
                    x-autofocus @keyup.escape="$el.form.reset(); $el.form.requestSubmit();"
                    @click.outside="$el.form.requestSubmit()" data-1p-ignore />
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
                    </footer>
                </div>
            </form>
        @endif
    </section>
</x-layout.app>
