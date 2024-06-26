<x-layout.app :title="$post->title">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ $post->title }}
                </h1>

                @can('update', $post)
                    <nav class="flex items-center gap-4 justify-end grow">
                        <x-button.primary :href="route('news.edit', $post)" :label="__('Edit')" />
                    </nav>
                @endcan
            </div>
        </header>

        @isset($post->image)
            <figure class="max-w-prose">
                <img src="{{ asset($post->image['file']) }}" width="{{ $post->image['width'] }}"
                    height="{{ $post->image['height'] }}" />
                @isset($post->image['caption'])
                    <figcaption>{{ $post->image['caption'] }}</figcaption>
                @endisset
            </figure>
        @endisset

        <div class="p-4 sm:p-8">
            <article class="prose dark:prose-invert max-w-prose">
                @include('news.partials.meta')
                <x-markdown :text="$post->body" />
            </article>
        </div>
    </section>
</x-layout.app>
