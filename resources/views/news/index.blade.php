<x-layout.app :title="__('News')">
    <section>
        <header class="p-4 sm:px-8 border-b sm:sticky sm:top-0 sm:z-10 bg-white dark:bg-gray-800">
            <h1 class="text-2xl font-medium flex items-center gap-3">
                <x-icon.news style="height: .75lh" class="fill-current" aria-hidden="true" />
                <span>@lang('News')</span>
            </h1>
        </header>
        <div class="p-4 sm:px-8 max-w-prose space-y-6">
            @foreach ($posts as $post)
                <article class="space-y-2">
                    <h2 class="text-xl sm:text-2xl font-medium"><a
                            href="{{ route('news.show', $post) }}">{{ $post->title }}</a></h2>
                    @include('news.partials.meta')
                    <div class="text-gray-700 dark:text-gray-300">
                        {!! $post->summary !!}
                    </div>
                    <p><a href="{{ route('news.show', $post) }}" class="underline font-medium">@lang('Read more...')</a></p>
                </article>
            @endforeach
        </div>
    </section>
</x-layout.app>
