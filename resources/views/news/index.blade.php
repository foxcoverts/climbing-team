<x-layout.app :title="__('News')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center gap-3">
                    <x-icon.news style="height: .75lh" class="fill-current" aria-hidden="true" />
                    <span>{{ __('News') }}</span>
                </h1>

                @can('create', \App\Models\NewsPost::class)
                    <nav class="flex items-center gap-4 justify-end grow">
                        <x-button.primary :href="route('news.create')" :label="__('New')" />
                    </nav>
                @endcan
            </div>
        </header>
        <div class="p-4 sm:px-8 space-y-6">
            @foreach ($posts as $post)
                <article class="space-y-2 max-w-prose">
                    <h2 class="text-xl sm:text-2xl font-medium"><a
                            href="{{ route('news.show', $post) }}">{{ $post->title }}</a></h2>
                    @include('news.partials.meta')
                    <div class="text-gray-700 dark:text-gray-300">
                        {!! $post->summary !!}
                    </div>
                    <p><a href="{{ route('news.show', $post) }}" class="underline font-medium">
                            {{ __('Read more...') }}</a></p>
                </article>
            @endforeach
        </div>
    </section>
</x-layout.app>
