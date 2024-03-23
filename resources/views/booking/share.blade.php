<x-layout.app :title="__('Share Booking')">
    <section class="p-4 sm:px-8">
        @include('booking.partials.header')

        <div class="flex flex-wrap gap-4">
            <div class="w-full max-w-xl">
                @include('booking.partials.details')

                <div class="space-y-2">
                    <div>
                        <x-input-label for="link">@lang('Share link')</x-input-label>
                        <x-text-input readonly id="link" name="link" class="w-full" :value="route('booking.preview', $booking)" />
                    </div>

                    <div>
                        <x-input-label for="post">@lang('Share post')</x-input-label>
                        <x-textarea readonly id="post" name="post" class="w-full">
                            {{ $post }}
                        </x-textarea>
                    </div>
                </div>

                <footer class="flex items-start gap-4 mt-4">
                    @if (config('app.facebook.group'))
                        <x-button.secondary href="{{ config('app.facebook.group') . '?should_open_composer=true' }}"
                            target="_blank">
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
