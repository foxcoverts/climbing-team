@use(App\Filament\Resources\NewsPostResource)
@use(App\Models\NewsPost)
<x-filament-widgets::widget>
    <x-filament::section icon="heroicon-o-newspaper">
        <x-slot name="heading">
            {{ __('Recent News') }}
        </x-slot>

        <div class="space-y-4">
            <h2 class="font-medium text-xl">{{ $title }}</h2>

            {!! $summary !!}

            <x-filament::grid default="2">
                <x-filament::grid.column>
                    <em class="text-sm">{{ __('Posted :ago.', compact('ago')) }}</em>
                </x-filament::grid.column>
                <x-filament::grid.column class="text-right">
                    <x-filament::link wire:click="$dispatch('open-modal', { id: 'recent-news' })"
                        icon="heroicon-m-sparkles" class="cursor-pointer">{{ __('Read more...') }}</x-filament::link>
                </x-filament::grid.column>
            </x-filament::grid>
        </div>
    </x-filament::section>

    <x-filament::modal id="recent-news" width="2xl" sticky-footer>
        <x-slot name="heading">
            <h1 class="font-medium text-3xl">{{ $title }}</h1>
        </x-slot>

        <div class="prose dark:prose-invert max-w-prose">
            <x-markdown :text="$body" />
        </div>

        <x-slot name="footerActions">
            <div class="text-sm grow">
                <x-markdown>{{ __('Posted :ago by **:author**.', compact('ago', 'author')) }}</x-markdown>
            </div>

            @can('viewAny', NewsPost::class)
                <x-filament::link :href="NewsPostResource::getUrl('index')" wire:navigate>{{ __('View all news') }}</x-filament::link>
            @endcan
        </x-slot>
    </x-filament::modal>
</x-filament-widgets::widget>