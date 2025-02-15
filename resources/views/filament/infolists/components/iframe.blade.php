<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    @if ($getState())
        <div
            {{
                $attributes
                    ->merge($getExtraAttributes(), escape: false)
                    ->class(['fi-in-iframe w-full'])
            }}
            x-data="{
                body: {{ Js::from($getState()) }},
                init() {
                    this.$refs.body.contentWindow.document.open('text/html', 'replace');
                    this.$refs.body.contentWindow.document.write(this.body);
                    this.$refs.body.contentWindow.document.close();
                },
            }"
        >
            <iframe class="w-full mt-1 h-dvh border border-gray-300 dark:border-gray-700 bg-white rounded-md shadow-sm" x-ref="body" src="about:blank"></iframe>
        </div>
    @elseif (($placeholder = $getPlaceholder()) !== null)
        <x-filament-infolists::entries.placeholder>
            {{ $placeholder }}
        </x-filament-infolists::entries.placeholder>
    @endif
</x-dynamic-component>