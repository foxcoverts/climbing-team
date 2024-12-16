<x-layout.app :title="__('Documents')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Documents') }}
                </h1>
            </div>
        </header>

        <div class="divide-y">
            @forelse ($documents as $document)
                <div class="py-2 px-4 sm:px-8 hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    @click="window.location={{ Js::from(route('document.show', $document)) }}">
                    <div class="max-w-prose flex justify-start gap-4">
                        <x-icon.file.pdf class="min-w-9 w-9 my-1 self-start" />
                        <div class="grow">
                            <h2 class="text-lg font-medium"><a
                                    href="{{ route('document.show', $document) }}">{{ $document->title }}</a></h2>
                            @if (!empty($document->description))
                                <div
                                    class="prose dark:prose-invert prose-p:my-2 prose-ul:my-2 prose-ol:my-2 prose-li:my-0">
                                    <x-markdown :text="$document->description" />
                                </div>
                            @endif
                            <p><dfn class="not-italic font-medium">{{ __('Updated') }}</dfn>:
                                <span x-data="{{ Js::from(['updated_at' => localDate($document->updated_at)]) }}"
                                    x-text="dateTimeString(updated_at)">{{ localDate($document->updated_at)->toDayDateTimeString() }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-2 px-4 sm:px-8">
                    <div class="max-w-prose">
                        {{ __('No documents have been uploaded yet.') }}
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</x-layout.app>
