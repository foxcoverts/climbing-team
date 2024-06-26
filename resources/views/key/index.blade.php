<x-layout.app :title="__('Keys')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Keys') }}
                </h1>

                @can('create', App\Models\Key::class)
                    <nav class="flex items-center gap-4 justify-end grow">
                        <x-button.primary :href="route('key.create')" :label="__('Add Key')" />
                    </nav>
                @endcan
            </div>
        </header>

        <div x-init x-merge="morph" id="keys" @key:updated="$ajax({{ Js::from(route('key.index')) }})">
            @forelse ($keys as $key)
                <div class="p-4 sm:px-8 border-b space-y-2">
                    <h2 class="text-lg font-medium leading-normal" id="{{ sprintf('key-%s-name', $key->id) }}">
                        @can('update', $key)
                            <a href="{{ route('key.edit', $key) }}"
                                x-target="{{ sprintf('key-%s-name', $key->id) }}">{{ $key->name }}</a>
                        @else
                            {{ $key->name }}
                        @endcan
                    </h2>

                    <p class="text-gray-900 dark:text-gray-100"><dfn
                            class="not-italic font-medium text-black dark:text-white">{{ __('Held by') }}:</dfn>
                        @can('view', $key->holder)
                            <a href="{{ route('user.show', $key->holder) }}">{{ $key->holder->name }}</a>
                        @else
                            {{ $key->holder->name }}
                        @endcan
                    </p>

                    @can('transfer', $key)
                        <x-button.primary class="gap-2 group" :href="route('key.transfer', $key)" x-target="transfer-key"
                            @ajax:before="$dispatch('dialog:open')">
                            <x-icon.transfer class="w-4 h-4 fill-current" />
                            <span class="hidden group-hover:block">{{ __('Transfer Key') }}</span>
                        </x-button.primary>
                    @endcan
                </div>
            @empty
                <div class="py-2 px-4 sm:px-8 border-b">
                    {{ __('You have no keys.') }}
                </div>
            @endforelse
        </div>

        <dialog x-init @dialog:open.window="$el.showModal()" @ajax:success="$el.close()"
            @click="if ($event.target === $el) $el.close()" class="bg-white dark:bg-gray-900">
            <form id="transfer-key"></form>
        </dialog>
    </section>
</x-layout.app>
