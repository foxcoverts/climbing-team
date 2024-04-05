<x-layout.app :title="__('Mail log')">
    <section>
        <header class="p-4 sm:px-8 bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 sm:z-50">
            <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Mail log')
            </h1>
        </header>

        <div class="text-gray-700 dark:text-gray-300 divide-y border-b" id="mails" x-merge="append">
            @forelse ($mails as $mail)
                <div class="p-2 px-4 sm:px-8 flex items-start gap-2 hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    @click="window.location='{{ route('mail.show', $mail) }}'">
                    <div>
                        <p @class(['py-0.5', 'text-sky-500' => $mail->isUnread()])>
                            @if ($mail->done)
                                <x-icon.checkmark-outline class="w-5 h-5 fill-current" />
                            @else
                                <x-icon.empty-outline class="w-5 h-5 fill-current" />
                            @endif
                        </p>
                    </div>
                    <div class="space-y-2 overflow-hidden">
                        <p class="flex items-center space-x-1">
                            <dfn class="not-italic font-medium">@lang('To'):</dfn>

                            @if ($mail->booking)
                                <x-icon.calendar.index class="w-5 h-5 fill-current" />
                                <span class="block truncate">
                                    {{ $mail->booking->activity }}
                                    -
                                    {{ localDate($mail->booking->start_at)->toFormattedDayDateString() }}
                                </span>
                            @else
                                <span class="block truncate">{{ $mail->to }}</span>
                            @endif
                        </p>

                        <p class="flex items-center space-x-1">
                            <dfn class="not-italic font-medium">@lang('From'):</dfn>

                            @if ($mail->user)
                                <x-icon.user-solid-square class="w-5 h-5 fill-current" />
                                <span class="block truncate">{{ $mail->user->name }}
                                    &lt;{{ $mail->user->email }}&gt;</span>
                            @else
                                <span class="block truncate">{{ $mail->from }}</span>
                            @endif
                        </p>

                        <p class="flex items-center space-x-1">
                            <dfn class="not-italic font-medium">@lang('Received'):</dfn>

                            <span>{{ localDate($mail->created_at) }}</span>
                        </p>
                    </div>
                </div>
            @empty
                <p class="px-3 py-2">@lang('No mail to see.')</p>
            @endforelse
        </div>

        {{ $mails->links('infinite-scroll', ['targets' => 'mails', 'loading' => 'mail-log.partials.loading']) }}
    </section>
</x-layout.app>
