<x-layout.app :title="$post->title">
    <section class="p-4 sm:px-8 grow">

        <article class="prose dark:prose-invert">
            <header>
                <h1>{{ $post->title }}</h1>
                @include('news.partials.meta')
            </header>
            {!! $post->content !!}
        </article>
    </section>
</x-layout.app>
