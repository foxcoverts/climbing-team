@use('Carbon\Carbon')
<p class="m-0 text-gray-700 dark:text-gray-300">
    @isset($post->author)
        <strong class="text-black dark:text-white">{{ $post->author }}</strong>
        @isset($post->date)
            •
        @endisset
    @endisset
    @isset($post->date)
        Posted
        <span class="cursor-default"
            title="{{ localDate($post->date)->toDayDateTimeString() }}">{{ localDate($post->date)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}</span>
    @endisset
</p>
