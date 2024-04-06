<x-layout.app :title="__('Keys')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-50">
            <div class="px-4 sm:px-8 flex items-center justify-between">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">@lang('Keys')</h1>

                @can('create', App\Models\Key::class)
                    <nav>
                        <x-button.primary :href="route('key.create')">@lang('Add Key')</x-button.primary>
                    </nav>
                @endcan
            </div>
        </header>

        <div x-init x-merge="morph" id="keys" @key:updated="$ajax({{ Js::from(route('key.index')) }})">
            @foreach ($keys as $key)
                <div class="py-2 px-4 sm:px-8 border-b space-y-1">
                    <h2 class="text-lg font-medium" id="{{ sprintf('key-%s-name', $key->id) }}">
                        <a href="{{ route('key.edit', $key) }}"
                            x-target="{{ sprintf('key-%s-name', $key->id) }}">{{ $key->name }}</a>
                    </h2>

                    <p class="text-gray-900 dark:text-gray-100"><dfn
                            class="not-italic font-medium text-black dark:text-white">@lang('Held by'):</dfn>
                        @can('view', $key->holder)
                            <a href="{{ route('user.show', $key->holder) }}">{{ $key->holder->name }}</a>
                        @else
                            {{ $key->holder->name }}
                        @endcan
                    </p>

                    @can('update', $key)
                        <x-button.primary class="gap-2 group" :href="route('key.transfer', $key)" x-target="transfer-key"
                            @ajax:before="$dispatch('dialog:open')">
                            <x-icon.transfer class="w-4 h-4 fill-current" />
                            <span class="hidden group-hover:block">@lang('Transfer Key')</span>
                        </x-button.primary>
                    @endcan
                </div>
            @endforeach
        </div>

        <dialog x-init @dialog:open.window="$el.showModal()" @ajax:success="$el.close()"
            @click="if ($event.target === $el) $el.close()" class="bg-white dark:bg-gray-900">
            <form id="transfer-key"></form>
        </dialog>
    </section>
</x-layout.app>
