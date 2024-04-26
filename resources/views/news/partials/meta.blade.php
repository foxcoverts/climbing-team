@use('Carbon\Carbon')
<p class="m-0 text-gray-700 dark:text-gray-300">
    @isset($post->author)
        <strong class="text-black dark:text-white">{{ $post->author->name }}</strong>
        •
    @endisset
    @lang('Posted')
    <span x-data="{{ Js::from(['start_at' => localDate($post->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
        class="cursor-help">{{ localDate($post->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}</span>
</p>
