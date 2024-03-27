@use('Carbon\Carbon')
<p class="m-0">
    @isset($post->author)
        <strong>{{ $post->author }}</strong>
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
