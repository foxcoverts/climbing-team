<x-layout.app :title="__('News')">
    <section class="p-4 sm:px-8 grow">
        <header>
            <h1 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100 flex items-center space-x-1">
                @lang('News')
            </h1>
        </header>

        <ul class="ml-8 mt-4 list-disc">
            @foreach ($posts as $post)
                <li><a href="{{ route('news.show', $post) }}">{{ $post->title }}</a></li>
            @endforeach
        </ul>
    </section>
</x-layout.app>
