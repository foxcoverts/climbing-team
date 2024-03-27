<x-layout.app :title="__('News')">
    <section class="p-4 sm:px-8 space-y-6 max-w-3xl">
        <header>
            <h1 class="text-2xl sm:text-3xl font-medium">@lang('News')</h1>
        </header>
        @foreach ($posts as $post)
            <article class='space-y-2'>
                <h2 class="text-xl sm:text-2xl font-medium"><a
                        href="{{ route('news.show', $post) }}">{{ $post->title }}</a></h2>
                @include('news.partials.meta')

                {!! $post->summary !!}
                <p><a href="{{ route('news.show', $post) }}" class="underline font-medium">@lang('Read more...')</a></p>
            </article>
        @endforeach
    </section>
</x-layout.app>
