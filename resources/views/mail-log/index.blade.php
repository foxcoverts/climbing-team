@use('Carbon\Carbon')
<x-layout.app :title="__('Mail log')">
    <section>
        <header class="bg-white dark:bg-gray-800 border-b sm:sticky sm:top-0 px-4 sm:px-8">
            <div class="py-2 min-h-16 flex flex-wrap items-center justify-between gap-2 max-w-prose">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-gray-100">
                    @lang('Mail log')
                </h1>
            </div>
        </header>

        <div class="text-gray-700 dark:text-gray-300 divide-y border-b" id="mails" x-merge="append">
            @forelse ($mails as $mail)
                <div class="py-2 px-4 sm:px-8 flex items-start gap-2 hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                    @click="window.location={{ Js::from(route('mail.show', $mail)) }}">
                    <div>
                        <p @class(['py-0.5', 'text-sky-500' => $mail->isUnread()])>
                            @if ($mail->done)
                                <x-icon.outline.checkmark class="w-5 h-5 fill-current" />
                            @else
                                <x-icon.outline class="w-5 h-5 fill-current" />
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
                                    <span x-data="{{ Js::from(['start_at' => localDate($mail->booking->start_at, $mail->booking->timezone)]) }}"
                                        x-text="dateString(start_at)">{{ localDate($mail->booking->start_at, $mail->booking->timezone)->toFormattedDayDateString() }}</span>
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

                            <span x-data="{{ Js::from(['start_at' => localDate($mail->created_at)]) }}" x-bind:title="dateTimeString(start_at)"
                                class="cursor-help">{{ ucfirst(localDate($mail->created_at)->ago(['options' => Carbon::JUST_NOW | Carbon::ONE_DAY_WORDS])) }}</span>

                        </p>
                    </div>
                </div>
            @empty
                <p class="py-2 px-4 sm:px-8">@lang('No mail to see.')</p>
            @endforelse
        </div>

        {{ $mails->links('infinite-scroll', ['targets' => 'mails', 'loading' => 'mail-log.partials.loading']) }}
    </section>
</x-layout.app>
