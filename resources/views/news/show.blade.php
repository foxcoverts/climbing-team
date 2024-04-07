<x-layout.app :title="$post->title">
    <section>
        <header class="p-4 sm:px-8 sm:sticky sm:top-0 sm:z-10 bg-white dark:bg-gray-800 border-b">
            <h1 class="text-2xl font-medium">
                {{ $post->title }}</h1>
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

        <article class="prose dark:prose-invert p-4 sm:p-8">
            @include('news.partials.meta')
            {!! $post->content !!}
        </article>
    </section>
</x-layout.app>
