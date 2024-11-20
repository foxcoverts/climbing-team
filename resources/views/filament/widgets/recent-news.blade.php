<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-newspaper">
        <x-slot name="heading">
            <x-filament::modal id="recent-news" width="2xl" sticky-header>
                <x-slot name="trigger">
                    {{ $title }}
                </x-slot>
            
                <x-slot name="heading">
                    {{ $title }}
                </x-slot>
                
                <div class="prose dark:prose-invert max-w-prose">
                    <p>{{ __(':Author posted :ago', compact('ago', 'author')) }}.</p>

                    <x-markdown :text="$body" />
                </div>

                <x-slot name="footerActions">
                    <x-filament::link :href="route('news.index')">{{ __('View all news') }}</x-filament::link>
                </x-slot>
            </x-filament::modal>
        </x-slot>

        {!! $summary !!}

        <p><x-filament::link :href="$link" wire:click.prevent="$dispatch('open-modal', { id: 'recent-news' })">{{ __('Read more...') }}</x-filament::link></p>
    </x-filament::section>
</x-filament-widgets::widget>
