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
                    <th
                        class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                        @lang('ID')</th>

                    <th
                        class="px-3 py-2 text-left text-nowrap sticky top-0 bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-300">
                        @lang('Created')</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 border-y border-gray-200">
                @forelse ($mails as $mail)
                    <tr class="hover:bg-gray-100 hover:dark:text-gray-200 dark:hover:bg-gray-700 cursor-pointer"
                        @click="window.location='{{ route('mail.show', $mail) }}'">
                        <td class="px-3 py-2"><a href="{{ route('mail.show', $mail) }}">{{ $mail->id }}</a>
                            @if ($mail->isUnread())
                                <x-badge :label="__('New')" color="lime" class="text-xs" />
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
