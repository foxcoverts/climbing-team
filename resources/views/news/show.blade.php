@use('Carbon\Carbon')
<x-layout.app :title="$post->title">
    <section class="p-4 sm:px-8 grow">

        <article class="prose dark:prose-invert">
            <header>
                <h1>{{ $post->title }}</h1>
                <div class="mt-1">
                    <p class="m-0">
                        <strong>{{ $post->author }}</strong> • Posted
                        <span
                            title="{{ localDate($post->date)->toDayDateTimeString() }}">{{ localDate($post->date)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS | Carbon::TWO_DAY_WORDS]) }}</span>
                    </p>
                </div>
            </header>
            {!! $post->content !!}
        </article>
    </section>
</x-layout.app>
