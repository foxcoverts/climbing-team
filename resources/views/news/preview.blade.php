@use('Carbon\Carbon')
<x-layout.guest :title="$post->title" :description="$post->summaryText" :updated="$post->date">
    @if (isset($post->image))
        <x-slot:image :width="$post->image['width']" :height="$post->image['height']" hero>
            {{ asset($post->image['file']) }}
        </x-slot:image>
    @else
        <x-slot:image width="744" height="328">
            {{ asset('images/news/fox-coverts-climbing-necker.png') }}
        </x-slot:image>
    @endif

    <div class="space-y-2">
        @isset($post->date)
            <p class="m-0 text-gray-700 dark:text-gray-300">
                @lang('Posted')
                <span class="cursor-default"
                    title="{{ localDate($post->date)->toDayDateTimeString() }}">{{ localDate($post->date)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}</span>
            </p>
        @endisset

        {!! $post->summary !!}
    </div>

    <div
        class="my-4 space-y-4 p-4 border text-black bg-slate-100 border-slate-400 dark:text-white dark:bg-slate-900 dark:border-slate-600">
        <div class="flex justify-center gap-4">
            <x-button.primary :href="route('login')">
                @lang('Read more')
            </x-button.primary>
        </div>

        <p class="text-sm text-center">@lang('Please login to view the full post.')</p>
    </div>
</x-layout.guest>