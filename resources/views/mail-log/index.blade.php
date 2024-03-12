<x-layout.app :title="__('Mail log')">
    <section class="p-4 sm:p-8">
        <header>
            <h2 class="text-2xl sm:text-3xl font-medium text-gray-900 dark:text-gray-100">
                @lang('Mail log')
            </h2>
        </header>

        <table class="w-full mt-6 text-gray-700 dark:text-gray-300 ">
            <thead>
                <tr>
                    <th class="pl-3 sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                        <x-icon.empty-outline class="w-5 h-5 fill-current" />
                    </th>
                    <th
                        class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                        @lang('To')</th>
                    <th
                        class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                        @lang('From')</th>
                    <th
                        class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                        @lang('Received')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 border-y border-gray-200">
                @forelse ($mails as $mail)
                    <tr class="hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                        @click="window.location='{{ route('mail.show', $mail) }}'">
                        <td @class(['pl-3', 'text-sky-500' => $mail->isUnread()])>
                            @if ($mail->done)
                                <x-icon.checkmark-outline class="w-5 h-5 fill-current" />
                            @else
                                <x-icon.question-outline class="w-5 h-5 fill-current" />
                            @endif
                        </td>
                        <td class="px-3 py-3">
                            @if ($mail->booking)
                                <div class="flex items-center space-x-1">
                                    <x-icon.calendar class="w-5 h-5 fill-current" />
                                    <span>
                                        {{ $mail->booking->activity }}
                                        -
                                        {{ localDate($mail->booking->start_at)->toFormattedDayDateString() }}
                                    </span>
                                </div>
                            @else
                                {{ $mail->to }}
                            @endif
                        </td>
                        <td class="px-3 py-3">
                            @if ($mail->user)
                                <div class="flex items-center space-x-1">
                                    <x-icon.user-solid-square class="w-5 h-5 fill-current" />
                                    <span>{{ $mail->user->name }} &lt;{{ $mail->user->email }}&gt;</span>
                                </div>
                            @else
                                {{ $mail->from }}
                            @endif
                        </td>
                        <td class="px-3 py-2">{{ localDate($mail->created_at) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-3 py-2">@lang('No mail found.')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </section>
</x-layout.app>
