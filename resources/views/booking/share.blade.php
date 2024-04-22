<x-layout.app :title="__('Share Booking')">
    <section>
        @include('booking.partials.header')

        <div class="p-4 sm:px-8">
            <div class="w-full max-w-prose space-y-4">
                <aside class="hidden sm:block">
                    @include('booking.partials.details')
                </aside>

                <div class="space-y-2">
                    <div class="border-b border-gray-800 dark:border-gray-200">
                        <h2 class="text-xl font-medium text-gray-800 dark:text-gray-200">@lang('Share Booking')</h2>
                    </div>

                    <div class="space-y-2">
                        <div x-data="{
                            copied: false,
                            text: {{ Js::from($link) }},
                            timeout: null,
                            copy() {
                                $clipboard(this.text);
                                this.copied = true;
                                clearTimeout(this.timeout);
                                this.timeout = setTimeout(() => {
                                    this.copied = false;
                                }, 3000);
                            },
                        }">
                            <x-input-label for="link">@lang('Share link')</x-input-label>
                            <div class="flex items-stretch mt-1">
                                <x-text-input readonly id="link" name="link" x-model.fill="text"
                                    class="flex-grow flex-shrink rounded-r-none" />
                                <x-button.primary class="rounded-l-none" @click="copy" title="Copy link" x-cloak>
                                    <x-icon.clipboard class="w-4 h-4 fill-current" x-show="!copied" />
                                    <x-icon.clipboard.check class="w-4 h-4 fill-gray-400 dark:fill-gray-600"
                                        x-show="copied" />
                                </x-button.primary>
                            </div>
                        </div>

                        <div x-data="{
                            copied: false,
                            text: {{ Js::from($post) }},
                            timeout: null,
                            copy() {
                                $clipboard(this.text);
                                this.copied = true;
                                clearTimeout(this.timeout);
                                this.timeout = setTimeout(() => {
                                    this.copied = false;
                                }, 3000);
                            },
                        }">
                            <x-input-label for="post">@lang('Share post')</x-input-label>
                            <div class="flex items-stretch mt-1">
                                <x-text-input id="post" name="post" x-model.fill="text"
                                    class="flex-grow flex-shrink rounded-r-none " />
                                <x-button.primary class="rounded-l-none" @click="copy" title="Copy post" x-cloak>
                                    <x-icon.clipboard class="w-4 h-4 fill-current" x-show="!copied" />
                                    <x-icon.clipboard.check class="w-4 h-4 fill-gray-400 dark:fill-gray-600"
                                        x-show="copied" />
                                </x-button.primary>
                            </div>
                        </div>
                    </div>
                </div>

                <footer class="flex flex-wrap items-start gap-4 mt-6">
                    @if (config('app.facebook.group'))
                        <x-button.secondary href="{{ config('app.facebook.group') . '?should_open_composer=true' }}"
                            target="_blank" class="order-last ml-auto whitespace-nowrap">
                            @lang('Open Facebook Group')
                            <x-icon.external-link class="ml-1 w-4 h-4 stroke-current"
                                aria-label="(opens in new window)" />
                        </x-button.secondary>
                    @endif
                    <x-button.secondary :href="route('booking.show', $booking)">
                        @lang('Back')
                    </x-button.secondary>
                </footer>
            </div>
        </div>
    </section>
</x-layout.app>
