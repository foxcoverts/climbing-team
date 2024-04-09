<x-layout.app :title="__('Deleted Documents')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-10 px-4 sm:px-8">
            <div class="flex items-center justify-between max-w-prose">
                <h1 class="text-2xl font-medium py-4 text-gray-900 dark:text-gray-100">
                    @lang('Deleted Documents')
                </h1>
            </div>
        </header>

        <div class="divide-y">
            @forelse ($documents as $document)
                <div class="py-2 px-4 sm:px-8 hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    @click="window.location={{ Js::from(route('trash.document.show', $document)) }}">
                    <div class="max-w-prose flex justify-start gap-4">
                        <x-icon.file.pdf class="min-w-9 w-9 my-1" />
                        <div class="grow">
                            <h2 class="text-lg font-medium"><a
                                    href="{{ route('trash.document.show', $document) }}">{{ $document->title }}</a></h2>
                            @if (!empty($booking->description))
                                <div
                                    class="prose dark:prose-invert prose-p:my-2 prose-ul:my-2 prose-ol:my-2 prose-li:my-0">
                                    <x-markdown :text="$booking->description" />
                                </div>
                            @endif
                            <p><dfn class="not-italic font-medium">@lang('Updated')</dfn>:
                                <span x-data="{{ Js::from(['updated_at' => localDate($document->updated_at)]) }}"
                                    x-text="dateTimeString(updated_at)">{{ localDate($document->updated_at)->toDayDateTimeString() }}</span>
                            </p>
                        </div>
                        @can('restore', $document)
                            <form method="POST" action="{{ route('trash.document.update', $document) }}"
                                x-data="{ submitted: false }" x-on:submit="setTimeout(() => submitted = true, 0)">
                                @csrf @method('PATCH')
                                <input type="hidden" name="deleted_at" value="0" />
                                <x-button.primary x-bind:disabled="submitted" :label="__('Restore')"
                                    x-text="submitted ? '{{ __('Please wait...') }}' : '{{ __('Restore') }}'" />
                            </form>
                        @endcan
                    </div>
                </div>
            @empty
                <div class="py-2 px-4 sm:px-8">
                    <div class="max-w-prose">
                        @lang('No documents have been deleted yet.')
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</x-layout.app>
