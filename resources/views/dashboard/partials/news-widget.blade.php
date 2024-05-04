@use('Carbon\Carbon')
@if ($post)
    <section class="p-4 sm:px-8 space-y-4">
        <h2 class="mb-6 text-2xl font-medium text-gray-900 dark:text-gray-100 flex items-center space-x-3">
            @if (isset($icon) && $icon)
                <x-dynamic-component :component="'icon.' . $icon" style="height: .75lh; width: .75lh" class="fill-current"
                    aria-hidden="true" />
            @endif
            <span>{{ $title }}</span>
        </h2>

        <div class="border divide-y max-w-prose">
            <h3><a href="{{ route($route, $post) }}"
                    class="flex flex-wrap gap-1 group justify-between px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                    <span class="font-medium group-hover:underline">{{ $post->title }}</span>
                    <span>{{ __('Posted') }} <span x-data="{{ Js::from(['start_at' => localDate($post->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
                            class="cursor-help">{{ localDate($post->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS]) }}</span></span>
                </a></h3>
            <div class="px-3 py-2">
                {!! $post->summary !!}
                <p class="text-right"><a href="{{ route($route, $post) }}"
                        class="underline font-medium">{{ __('Read more...') }}</a></p>
            </div>
        </div>

        <p><a href="{{ route($more['route']) }}" class="hover:underline">{{ $more['label'] }}</a></p>
    </section>
@endif
